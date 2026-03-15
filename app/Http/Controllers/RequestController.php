<?php

namespace App\Http\Controllers;

use App\Models\Request as RequestModel;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;

class RequestController extends Controller
{
    public function index()
    {
        try {
            $data = RequestModel::with(['status', 'user', 'payment', 'documentRequests.document'])->get();

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

    public function store(HttpRequest $request)
    {
        $validated = $request->validate([
            'qr_code' => 'nullable|string|max:255',
            'tracking_number' => 'required|string|max:255|unique:request,tracking_number',
            'user_id' => 'required|integer|exists:users,id',
            'service_id' => 'required|integer',
            'appointment_id' => 'nullable|integer',
            'status_id' => 'required|integer|exists:status,id',
        ]);

        $requestModel = RequestModel::create($validated);

        return response()->json($requestModel->load(['status', 'user', 'payment', 'documentRequests.document']), Response::HTTP_CREATED);
    }

    public function show(RequestModel $request)
    {
        return response()->json(
            $request->load(['status', 'user', 'payment', 'documentRequests.document']),
            Response::HTTP_OK
        );
    }

    public function edit(RequestModel $request)
    {
        //
    }

    public function update(HttpRequest $httpRequest, RequestModel $request)
    {
        $validated = $httpRequest->validate([
            'qr_code' => 'nullable|string|max:255',
            'tracking_number' => 'required|string|max:255|unique:request,tracking_number,' . $request->id,
            'user_id' => 'required|integer|exists:users,id',
            'service_id' => 'required|integer',
            'appointment_id' => 'nullable|integer',
            'status_id' => 'required|integer|exists:status,id',
        ]);

        $request->update($validated);

        return response()->json($request->load(['status', 'user', 'payment', 'documentRequests.document']), Response::HTTP_OK);
    }

    public function destroy(RequestModel $request)
    {
        $request->delete();

        return response()->json(['message' => 'Request deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
