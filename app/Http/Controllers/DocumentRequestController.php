<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class DocumentRequestController extends Controller
{
    public function index()
    {
        try {
            $data = DocumentRequest::with(['request.status', 'document'])->get();

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

        $documentRequest = DocumentRequest::create($validated)->load(['request.status', 'document']);

        return response()->json($documentRequest, Response::HTTP_CREATED);
    }

    public function show(DocumentRequest $documentRequest)
    {
        return response()->json($documentRequest->load(['request.status', 'document']), Response::HTTP_OK);
    }

    public function edit(DocumentRequest $documentRequest)
    {
        //
    }

    public function update(Request $request, DocumentRequest $documentRequest)
    {
        $validated = $this->validatedData($request, $documentRequest);

        $documentRequest->update($validated);

        return response()->json($documentRequest->load(['request.status', 'document']), Response::HTTP_OK);
    }

    public function destroy(DocumentRequest $documentRequest)
    {
        $documentRequest->delete();

        return response()->json(['message' => 'Document request deleted successfully'], Response::HTTP_NO_CONTENT);
    }

    private function validatedData(Request $request, ?DocumentRequest $documentRequest = null): array
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
