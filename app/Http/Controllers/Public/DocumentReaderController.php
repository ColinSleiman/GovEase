<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentReaderController extends Controller
{
    // Show upload form
    public function create()
    {
        return view('documents.upload');
    }

    // Upload document and save
    public function upload(Request $request)
    {
        if ($request->hasFile('document')) {
            $file = $request->file('document');

            $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $file->getClientOriginalName());

            $folder = storage_path('app/document-reader');

            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            $file->move($folder, $fileName);
        }

        return redirect()->route('document.reader.create')->with('success', 'Document uploaded successfully.');
    }

    //show the uploaded docs only for admins
    public function index(Request $request)
    {
        if (!$this->isAdministrator()) {
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
                        $extension == 'gif' ||
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

    //preview pdf only eza role admin
    public function preview($fileName)
    {
        if (!$this->isAdministrator()) {
            abort(403, 'Unauthorized');
        }

        $fileName = basename($fileName);
        $path = storage_path('app/document-reader/' . $fileName);

        if (!file_exists($path)) {
            abort(404);
        }

        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (
            $extension == 'pdf' ||
            $extension == 'jpg' ||
            $extension == 'jpeg' ||
            $extension == 'png' ||
            $extension == 'gif' ||
            $extension == 'webp'
        ) {
            return response()->file($path);
        }

        return redirect()->route('admin.document.reader');
    }

    //download document only if user is admin
    public function download($fileName)
    {
        if (!$this->isAdministrator()) {
            abort(403, 'Unauthorized');
        }

        $fileName = basename($fileName);
        $path = storage_path('app/document-reader/' . $fileName);

        if (!file_exists($path)) {
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
