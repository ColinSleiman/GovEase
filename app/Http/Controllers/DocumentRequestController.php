<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DocumentRequestController extends Controller
{
    // Display all document requests
    public function index()
    {
        try {
            $data = DocumentRequest::all();
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
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
            'request_id',
            'document_id',
        ]);

        $documentRequest = DocumentRequest::create($validated);

        return response()->json($documentRequest, Response::HTTP_CREATED);
    }

    // Display a specific document request
    public function show(DocumentRequest $documentRequest)
    {
        return response()->json($documentRequest, Response::HTTP_OK);
    }

    // Show the form for editing a document request
    public function edit(DocumentRequest $documentRequest)
    {
        //
    }

    // Update a document request
    public function update(Request $request, DocumentRequest $documentRequest)
    {
        $validated = $request->validate([
            'request_id',
            'document_id',
        ]);

        $documentRequest->update($validated);

        return response()->json($documentRequest, Response::HTTP_OK);
    }

    // Delete a document request
    public function destroy(DocumentRequest $documentRequest)
    {
        $documentRequest->delete();

        return response()->json(['message' => 'DocumentRequest deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
