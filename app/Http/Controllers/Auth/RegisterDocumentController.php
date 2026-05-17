<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\DocumentReaderService;
use Illuminate\Http\Request;

class RegisterDocumentController extends Controller
{
    public function __construct(
        private DocumentReaderService $documentReader
    ) {}

    public function scan(Request $request)
    {
        $validated = $request->validate([
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:15360'],
        ]);

        try {
            $fileName = $this->documentReader->storeUploadedFile($validated['document']);
            $analysis = $this->documentReader->analyzeDocument($fileName);

            if (($analysis['status'] ?? '') === 'error') {
                $this->documentReader->deleteDocument($fileName);

                return $this->redirectWithDocumentError(
                    $analysis['message'] ?? 'Could not analyze the uploaded document.'
                );
            }

            $details = $this->documentReader->extractRegistrationDetails($analysis);

            if ($details['firstName'] === '' || $details['lastName'] === '') {
                $this->documentReader->deleteDocument($fileName);

                return $this->redirectWithDocumentError(
                    'We could not read a first and last name from your document. Try a clearer photo or register manually.'
                );
            }

            $request->session()->put('register_document_scan', [
                'firstName' => $details['firstName'],
                'lastName' => $details['lastName'],
                'fileName' => $fileName,
                'analysis' => $analysis,
            ]);

            return redirect()
                ->route('portal.access')
                ->with('open_register_tab', true)
                ->with('register_mode', 'document');
        } catch (\Throwable $exception) {
            report($exception);

            return $this->redirectWithDocumentError(
                'Document scan failed. Please try again or register manually.'
            );
        }
    }

    public function cancel(Request $request)
    {
        $scan = $request->session()->get('register_document_scan');

        if (is_array($scan) && ! empty($scan['fileName'])) {
            $this->documentReader->deleteDocument($scan['fileName']);
        }

        $request->session()->forget('register_document_scan');

        return redirect()
            ->route('portal.access')
            ->with('open_register_tab', true)
            ->with('register_mode', 'document');
    }

    private function redirectWithDocumentError(string $message)
    {
        return redirect()
            ->route('portal.access')
            ->withErrors(['document' => $message])
            ->with('open_register_tab', true)
            ->with('register_mode', 'document');
    }
}
