<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
            'file_path' => 'required|string',
            'document_type' => 'required|string',
            'uploaded_by' => 'required|exists:users,id',
        ]);

        $document = Document::create($validated);

        return response()->json($document, Response::HTTP_CREATED);
    }

    // Display a specific document
    public function show(Document $document)
    {
        return response()->json($document, Response::HTTP_OK);
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
            'file_path',
            'document_type',
            'uploaded_by',
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
}
