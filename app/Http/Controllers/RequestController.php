<?php

namespace App\Http\Controllers;

use App\Models\Request;
use Illuminate\Http\Response;

class RequestController extends Controller
{
    // Display all requests
    public function index()
    {
        try {
            $data = Request::all();
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Show the form for creating a new request
    public function create()
    {
        //
    }

    // Store a newly created request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'qr_code',
            'tracking_number',
            'user_id',
            'service_id',
            'appointment_id',
            'status_id',
        ]);

        $req = Request::create($validated);

        return response()->json($req, Response::HTTP_CREATED);
    }

    // Display a specific request
    public function show(Request $request)
    {
        return response()->json($request, Response::HTTP_OK);
    }

    // Show the form for editing a request
    public function edit(Request $request)
    {
        //
    }

    // Update a request
    public function update(Request $requestData, \App\Models\Request $request)
    {
        $validated = $requestData->validate([
            'qr_code',
            'tracking_number',
            'user_id',
            'service_id',
            'appointment_id',
            'status_id',
        ]);

        $request->update($validated);

        return response()->json($request, Response::HTTP_OK);
    }

    // Delete a request
    public function destroy(Request $request)
    {
        $request->delete();

        return response()->json(['message' => 'Request deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
