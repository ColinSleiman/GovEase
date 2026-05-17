<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\DocumentReaderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentReaderController extends Controller
{
    public function __construct(
        private DocumentReaderService $documentReader
    ) {}

    public function index(Request $request)
    {
        if (! $this->isAdministrator()) {
            abort(403, 'Unauthorized');
        }

        $folder = storage_path('app/document-reader');

        $documents = [];
        $previewDocument = null;

        if (is_dir($folder)) {
            $files = glob($folder . '/*');

            foreach ($files as $file) {
                if (is_file($file)) {
                    $fileName = basename($file);
                    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    $canPreview = false;
                    $type = 'Document';

                    if ($extension == 'pdf') {
                        $canPreview = true;
                        $type = 'PDF';
                    }

                    if (
                        $extension == 'jpg' ||
                        $extension == 'jpeg' ||
                        $extension == 'png' ||
                        $extension == 'webp'
                    ) {
                        $canPreview = true;
                        $type = 'Image';
                    }

                    $document = [
                        'name' => $fileName,
                        'size' => round(filesize($file) / 1024, 2) . ' KB',
                        'extension' => $extension,
                        'type' => $type,
                        'canPreview' => $canPreview,
                        'analysis' => $this->documentReader->loadAnalysis($fileName),
                        'uploader' => $this->documentReader->loadUploaderMetadata($fileName),
                    ];

                    $documents[] = $document;

                    if ($request->query('preview') == $fileName && $canPreview == true) {
                        $previewDocument = $document;
                    }
                }
            }
        }

        return view('documents.reader', [
            'documents' => $documents,
            'previewDocument' => $previewDocument,
        ]);
    }

    public function preview($fileName)
    {
        if (! $this->isAdministrator()) {
            abort(403, 'Unauthorized');
        }

        $fileName = basename($fileName);
        $path = storage_path('app/document-reader/' . $fileName);

        if (! file_exists($path)) {
            abort(404);
        }

        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (
            $extension == 'pdf' ||
            $extension == 'jpg' ||
            $extension == 'jpeg' ||
            $extension == 'png' ||
            $extension == 'webp'
        ) {
            return response()->file($path);
        }

        return redirect()->route('admin.document.reader');
    }

    public function download($fileName)
    {
        if (! $this->isAdministrator()) {
            abort(403, 'Unauthorized');
        }

        $fileName = basename($fileName);
        $path = storage_path('app/document-reader/' . $fileName);

        if (! file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    private function isAdministrator()
    {
        return Auth::check()
            && Auth::user()->role
            && Auth::user()->role->name == 'Administrator';
    }
}
