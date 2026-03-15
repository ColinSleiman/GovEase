<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Validation\Rule;

class ServiceRequestController extends Controller
{
    public function index(): JsonResponse
    {
        $requests = ServiceRequest::query()
            ->with(['status', 'user', 'payment', 'documentRequests.document'])
            ->latest('id')
            ->get();

        return response()->json($requests);
    }

    public function store(HttpRequest $request): JsonResponse
    {
        $validated = $this->validatedData($request);

        $serviceRequest = ServiceRequest::create($validated)->load([
            'status',
            'user',
            'payment',
            'documentRequests.document',
        ]);

        return response()->json($serviceRequest, 201);
    }

    public function show(ServiceRequest $serviceRequest): JsonResponse
    {
        return response()->json($serviceRequest->load([
            'status',
            'user',
            'payment',
            'documentRequests.document',
        ]));
    }

    public function update(HttpRequest $request, ServiceRequest $serviceRequest): JsonResponse
    {
        $validated = $this->validatedData($request, $serviceRequest);

        $serviceRequest->update($validated);

        return response()->json($serviceRequest->load([
            'status',
            'user',
            'payment',
            'documentRequests.document',
        ]));
    }

    public function destroy(ServiceRequest $serviceRequest): JsonResponse
    {
        $serviceRequest->delete();

        return response()->json(status: 204);
    }

    private function validatedData(HttpRequest $request, ?ServiceRequest $serviceRequest = null): array
    {
        return $request->validate([
            'qr_code' => ['nullable', 'string', 'max:255'],
            'tracking_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('request', 'tracking_number')->ignore($serviceRequest?->id),
            ],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'service_id' => ['required', 'integer'],
            'appointment_id' => ['nullable', 'integer'],
            'status_id' => ['required', 'integer', 'exists:status,id'],
        ]);
    }
}
