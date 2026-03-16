<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StatusController extends Controller
{
    // Display all statuses
    public function index()
    {
        try {
            $data = Status::all();
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Show the form for creating a new status
    public function create()
    {
        //
    }

    // Store a newly created status
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name',
        ]);

        $status = Status::create($validated);

        return response()->json($status, Response::HTTP_CREATED);
    }

    // Display a specific status
    public function show(Status $status)
    {
        return response()->json($status, Response::HTTP_OK);
    }

    // Show the form for editing a status
    public function edit(Status $status)
    {
        //
    }

    // Update a status
    public function update(Request $request, Status $status)
    {
        $validated = $request->validate([
            'name',
        ]);

        $status->update($validated);

        return response()->json($status, Response::HTTP_OK);
    }

    // Delete a status
    public function destroy(Status $status)
    {
        $status->delete();

        return response()->json(['message' => 'Status deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}

