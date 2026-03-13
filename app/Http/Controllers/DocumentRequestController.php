<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Validation\Rule;

class DocumentRequestController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            DocumentRequest::query()
                ->with(['request.status', 'document'])
                ->latest('id')
                ->get()
        );
    }

    public function store(HttpRequest $request): JsonResponse
    {
        $validated = $this->validatedData($request);

        $documentRequest = DocumentRequest::create($validated)->load(['request.status', 'document']);

        return response()->json($documentRequest, 201);
    }

    public function show(DocumentRequest $documentRequest): JsonResponse
    {
        return response()->json($documentRequest->load(['request.status', 'document']));
    }

    public function update(HttpRequest $request, DocumentRequest $documentRequest): JsonResponse
    {
        $validated = $this->validatedData($request, $documentRequest);

        $documentRequest->update($validated);

        return response()->json($documentRequest->load(['request.status', 'document']));
    }

    public function destroy(DocumentRequest $documentRequest): JsonResponse
    {
        $documentRequest->delete();

        return response()->json(status: 204);
    }

    private function validatedData(HttpRequest $request, ?DocumentRequest $documentRequest = null): array
    {
        return $request->validate([
            'request_id' => [
                'required',
                'integer',
                'exists:request,id',
                Rule::unique('document_request')
                    ->where(fn ($query) => $query->where('document_id', $request->input('document_id')))
                    ->ignore($documentRequest?->id),
            ],
            'document_id' => ['required', 'integer', 'exists:document,id'],
        ]);
    }
}
