<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;

class DocumentController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Document::query()
                ->with(['uploadedBy', 'createdBy', 'documentRequests'])
                ->latest('id')
                ->get()
        );
    }

    public function store(HttpRequest $request): JsonResponse
    {
        $validated = $this->validatedData($request);

        $document = Document::create($validated)->load(['uploadedBy', 'createdBy', 'documentRequests']);

        return response()->json($document, 201);
    }

    public function show(Document $document): JsonResponse
    {
        return response()->json($document->load(['uploadedBy', 'createdBy', 'documentRequests']));
    }

    public function update(HttpRequest $request, Document $document): JsonResponse
    {
        $validated = $this->validatedData($request);

        $document->update($validated);

        return response()->json($document->load(['uploadedBy', 'createdBy', 'documentRequests']));
    }

    public function destroy(Document $document): JsonResponse
    {
        $document->delete();

        return response()->json(status: 204);
    }

    private function validatedData(HttpRequest $request): array
    {
        return $request->validate([
            'file_path' => ['required', 'string', 'max:2048'],
            'document_type' => ['required', 'string', 'max:255'],
            'uploaded_by' => ['nullable', 'integer', 'exists:users,id'],
            'created_by' => ['nullable', 'integer', 'exists:users,id'],
        ]);
    }
}
