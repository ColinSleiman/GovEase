<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DocumentController extends Controller
{
    public function index()
    {
        try {
            $data = Document::with(['uploadedBy', 'createdBy', 'documentRequests'])->get();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        $document = Document::create($validated)->load(['uploadedBy', 'createdBy', 'documentRequests']);

        return response()->json($document, Response::HTTP_CREATED);
    }

    public function show(Document $document)
    {
        return response()->json($document->load(['uploadedBy', 'createdBy', 'documentRequests']), Response::HTTP_OK);
    }

    public function edit(Document $document)
    {
        //
    }

    public function update(Request $request, Document $document)
    {
        $validated = $this->validatedData($request);

        $document->update($validated);

        return response()->json($document->load(['uploadedBy', 'createdBy', 'documentRequests']), Response::HTTP_OK);
    }

    public function destroy(Document $document)
    {
        $document->delete();

        return response()->json(['message' => 'Document deleted successfully'], Response::HTTP_NO_CONTENT);
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'file_path' => ['required', 'string', 'max:2048'],
            'document_type' => ['required', 'string', 'max:255'],
            'uploaded_by' => ['nullable', 'integer', 'exists:users,id'],
            'created_by' => ['nullable', 'integer', 'exists:users,id'],
        ]);
    }
}
