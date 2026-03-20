<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentRequest;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DocumentRequestController extends Controller
{
    // Display all document requests
    public function index()
    {
        $data = DocumentRequest::with(['request', 'document'])->get();
        return response()->json($data, Response::HTTP_OK);
    }

    // Show the form for creating a new document request
    public function create()
    {
        //
    }

    // Store a newly created document request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|exists:requests,id',
            'document_id' => 'required|exists:documents,id',
        ]);

        $documentRequest = DocumentRequest::create($validated);

        return response()->json(
            $documentRequest->load(['request', 'document']),
            Response::HTTP_CREATED
        );
    }

    // Display a specific document request
    public function show(RequestModel $requestModel, Document $document)
    {
        $documentRequest = DocumentRequest::where('request_id', $requestModel->id)
            ->where('document_id', $document->id)
            ->firstOrFail();

        return response()->json(
            $documentRequest->load(['request', 'document']),
            Response::HTTP_OK
        );
    }

    // Show the form for editing a document request
    public function edit(DocumentRequest $documentRequest)
    {
        //
    }

    // Update a document request
    public function update(Request $request, RequestModel $requestModel, Document $document)
    {
        $validated = $request->validate([
            'request_id' => 'sometimes|required|exists:requests,id',
            'document_id' => 'sometimes|required|exists:documents,id',
        ]);

        $existing = DocumentRequest::where('request_id', $requestModel->id)
            ->where('document_id', $document->id)
            ->firstOrFail();

        $newRequestId = $validated['request_id'] ?? $requestModel->id;
        $newDocumentId = $validated['document_id'] ?? $document->id;

        if ($newRequestId !== $requestModel->id || $newDocumentId !== $document->id) {
            DocumentRequest::where('request_id', $requestModel->id)
                ->where('document_id', $document->id)
                ->delete();
            $existing = DocumentRequest::firstOrCreate([
                'request_id' => $newRequestId,
                'document_id' => $newDocumentId,
            ]);
        }

        return response()->json(
            $existing->load(['request', 'document']),
            Response::HTTP_OK
        );
    }

    // Delete a document request
    public function destroy(RequestModel $requestModel, Document $document)
    {
        DocumentRequest::where('request_id', $requestModel->id)
            ->where('document_id', $document->id)
            ->delete();

        return response()->json(['message' => 'DocumentRequest deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
