<?php

namespace App\Http\Controllers;

use App\Models\Request as RequestModel;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;

class RequestController extends Controller
{
    // Display all requests
    public function index()
    {
        try {
            $data = RequestModel::with(['status', 'service', 'appointment', 'payment', 'users'])->get();
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
        return response()->json(['message' => 'Not implemented'], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    // Store a newly created request
    public function store(HttpRequest $request)
    {
        $validated = $request->validate([
            'qr_code' => 'nullable|string',
            'tracking_number' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_id' => 'required|exists:appointments,id',
            'status_id' => 'required|exists:statuses,id',
        ]);

        $userId = $validated['user_id'];
        unset($validated['user_id']);

        $req = RequestModel::create($validated);
        $req->users()->syncWithoutDetaching([$userId]);

        return response()->json(
            $req->load(['status', 'service', 'appointment', 'payment', 'users']),
            Response::HTTP_CREATED
        );
    }

    // Display a specific request
    public function show(RequestModel $request)
    {
        return response()->json(
            $request->load(['status', 'service', 'appointment', 'payment', 'users']),
            Response::HTTP_OK
        );
    }

    // Show the form for editing a request
    public function edit(RequestModel $request)
    {
        return response()->json(['message' => 'Not implemented'], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    // Update a request
    public function update(HttpRequest $requestData, RequestModel $request)
    {
        $validated = $requestData->validate([
            'qr_code' => 'sometimes|nullable|string',
            'tracking_number' => 'sometimes|required|string',
            'user_id' => 'sometimes|exists:users,id',
            'service_id' => 'sometimes|exists:services,id',
            'appointment_id' => 'sometimes|exists:appointments,id',
            'status_id' => 'sometimes|exists:statuses,id',
        ]);

        if (array_key_exists('user_id', $validated)) {
            $userId = $validated['user_id'];
            unset($validated['user_id']);
        } else {
            $userId = null;
        }

        $request->update($validated);

        if ($userId !== null) {
            $request->users()->syncWithoutDetaching([$userId]);
        }

        return response()->json(
            $request->load(['status', 'service', 'appointment', 'payment', 'users']),
            Response::HTTP_OK
        );
    }

    // Delete a request
    public function destroy(RequestModel $request)
    {
        $request->delete();

        return response()->json(['message' => 'Request deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
