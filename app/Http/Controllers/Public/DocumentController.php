<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // Display all documents
    public function index()
    {
        try {
            $data = Document::all();
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Show the form for creating a new document
    public function create()
    {
        //
    }

    // Store a newly created document
    public function store(Request $request)
    {
        $validated = $request->validate([
            'file_path' => ['required', 'string', 'max:255'],
            'document_type' => ['required', 'string', 'max:255'],
            'uploaded_by' => ['required', 'exists:users,id'],
        ]);

        $document = Document::create($validated);

        return response()->json($document, Response::HTTP_CREATED);
    }

    // Display a specific document
    public function show(Document $document)
    {
        return response()->json($document, Response::HTTP_OK);
    }

    public function preview(Document $document)
    {
        $user = Auth::user();
        [$document, $linkedRequest] = $this->loadDocumentContext($document);

        if (! $this->canPreviewDocument($user, $linkedRequest)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if ($linkedRequest && $this->isGeneratedDocument($document->document_type)) {
            return $this->renderGeneratedDocument($document, $linkedRequest);
        }

        if (! Storage::disk('public')->exists($document->file_path)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return response()->file(Storage::disk('public')->path($document->file_path));
    }

    public function download(Document $document)
    {
        $user = Auth::user();
        [$document, $linkedRequest] = $this->loadDocumentContext($document);

        if (! $this->canPreviewDocument($user, $linkedRequest)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if (! Storage::disk('public')->exists($document->file_path)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return Storage::disk('public')->download(
            $document->file_path,
            $this->downloadFileName($document, $linkedRequest)
        );
    }

    // Show the form for editing a document
    public function edit(Document $document)
    {
        //
    }

    // Update a document
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'file_path' => ['sometimes', 'required', 'string', 'max:255'],
            'document_type' => ['sometimes', 'required', 'string', 'max:255'],
            'uploaded_by' => ['sometimes', 'required', 'exists:users,id'],
        ]);

        $document->update($validated);

        return response()->json($document, Response::HTTP_OK);
    }

    // Delete a document
    public function destroy(Document $document)
    {
        $document->delete();

        return response()->json(['message' => 'Document deleted successfully'], Response::HTTP_NO_CONTENT);
    }

    private function canPreviewDocument($user, $linkedRequest): bool
    {
        if (! $user || ! $linkedRequest) {
            return false;
        }

        $roleName = strtolower((string) $user->role?->name);

        if ($roleName === 'administrator') {
            return true;
        }

        if ($roleName === 'citizen') {
            return (int) $linkedRequest->user_id === (int) $user->id;
        }

        if ($roleName === 'officestaff') {
            return (int) ($linkedRequest->service?->office_id ?? 0) === (int) ($user->office_id ?? 0);
        }

        return false;
    }

    private function loadDocumentContext(Document $document): array
    {
        $document->load([
            'documentRequests.request.user',
            'documentRequests.request.status',
            'documentRequests.request.payment',
            'documentRequests.request.service.office.municipality',
            'documentRequests.request.reviewer',
        ]);

        $linkedRequest = $document->documentRequests
            ->map(fn ($documentRequest) => $documentRequest->request)
            ->filter()
            ->first();

        return [$document, $linkedRequest];
    }

    private function isGeneratedDocument(?string $documentType): bool
    {
        return in_array($documentType, [
            'Generated Certificate PDF',
            'Generated Payment Receipt PDF',
            'Generated Approval PDF',
        ], true);
    }

    private function renderGeneratedDocument(Document $document, $linkedRequest)
    {
        $view = match ($document->document_type) {
            'Generated Certificate PDF' => 'documents.generated.certificate',
            'Generated Payment Receipt PDF' => 'documents.generated.payment-receipt',
            'Generated Approval PDF' => 'documents.generated.approval',
            default => null,
        };

        if (! $view) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return response()
            ->view($view, [
                'document' => $document,
                'requestData' => $linkedRequest,
            ])
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    private function downloadFileName(Document $document, $linkedRequest): string
    {
        $trackingNumber = $linkedRequest?->tracking_number ?: ('document-' . $document->id);
        $slug = strtolower((string) preg_replace('/[^a-z0-9]+/i', '-', $document->document_type));
        $slug = trim($slug, '-');

        return $trackingNumber . '-' . ($slug ?: 'file') . '.pdf';
    }
}
