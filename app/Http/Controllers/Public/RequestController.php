<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentRequest;
use App\Models\Office;
use App\Models\Request as CitizenRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    // Display all requests
    public function index()
    {
        try {
            $user = Auth::user();
            $roleName = $this->getRoleName();
            $query = CitizenRequest::query()
                ->with(['status', 'service.office', 'service.serviceCategory', 'user', 'reviewer', 'documents'])
                ->latest();

            if ($roleName === 'citizen') {
                $query->where('user_id', $user->id);
            }

            if ($roleName === 'officestaff') {
                $query->whereHas('service', function ($serviceQuery) use ($user) {
                    $serviceQuery->where('office_id', $user->office_id);
                });
            }

            $data = $query->get();

            if (!request()->expectsJson() && $roleName === 'citizen') {
                return view('citizen.requests.index', [
                    'title' => 'My Requests',
                    'data' => $data,
                ]);
            }

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
        if ($this->getRoleName() !== 'citizen') {
            return response()->json([
                'message' => 'Only citizens can create requests.',
            ], Response::HTTP_FORBIDDEN);
        }

        $offices = Office::query()->orderBy('name')->get(['id', 'name']);
        $categories = ServiceCategory::query()->orderBy('name')->get(['id', 'name']);
        $services = Service::query()
            ->with(['office:id,name', 'serviceCategory:id,name'])
            ->orderBy('name')
            ->get(['id', 'name', 'office_id', 'service_category_id']);
        $servicesForJs = [];
        foreach ($services as $service) {
            $servicesForJs[] = [
                'id' => $service->id,
                'name' => $service->name,
                'office_id' => $service->office_id,
                'service_category_id' => $service->service_category_id,
                'office_name' => $service->office?->name,
                'category_name' => $service->serviceCategory?->name,
            ];
        }

        if (!request()->expectsJson()) {
            return view('citizen.requests.create', [
                'title' => 'Submit Request',
                'offices' => $offices,
                'categories' => $categories,
                'services' => $services,
                'servicesForJs' => $servicesForJs,
            ]);
        }

        return response()->json([
            'offices' => $offices,
            'categories' => $categories,
            'services' => $services,
            'servicesForJs' => $servicesForJs,
        ], Response::HTTP_OK);
    }

    // Store a newly created request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'office_id' => ['required', 'exists:offices,id'],
            'service_category_id' => ['required', 'exists:service_categories,id'],
            'service_id' => ['required', 'exists:services,id'],
            'status_note' => ['nullable', 'string', 'max:1000'],
            'documents' => ['required', 'array', 'min:1'],
            'documents.*' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'document_type' => ['nullable', 'string', 'max:255'],
        ]);

        if ($this->getRoleName() !== 'citizen') {
            return response()->json([
                'message' => 'Only citizens can create requests.',
            ], Response::HTTP_FORBIDDEN);
        }

        $pendingStatus = Status::query()->where('name', 'Pending')->first();
        if (!$pendingStatus) {
            return response()->json([
                'message' => 'Pending status is missing. Please run database seeder first.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $selectedService = Service::query()
            ->where('id', $validated['service_id'])
            ->where('office_id', $validated['office_id'])
            ->where('service_category_id', $validated['service_category_id'])
            ->first();

        if (!$selectedService) {
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Selected service does not match the chosen office and category.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return back()
                ->withErrors([
                    'service_id' => 'Selected service does not match the chosen office and category.',
                ])
                ->withInput();
        }

        $req = DB::transaction(function () use ($validated, $pendingStatus) {
            $createdRequest = CitizenRequest::create([
                'qr_code' => null,
                'tracking_number' => $this->generateTrackingNumber(),
                'user_id' => Auth::id(),
                'service_id' => $validated['service_id'],
                'status_id' => $pendingStatus->id,
                'status_note' => $validated['status_note'] ?? null,
            ]);

            $documentType = $validated['document_type'] ?? 'Request Attachment';
            foreach ($validated['documents'] as $uploadedFile) {
                $path = $uploadedFile->store('request-documents', 'public');

                $document = Document::create([
                    'file_path' => $path,
                    'document_type' => $documentType,
                    'uploaded_by' => Auth::id(),
                ]);

                DocumentRequest::create([
                    'request_id' => $createdRequest->id,
                    'document_id' => $document->id,
                ]);
            }

            return $createdRequest;
        });

        if (!request()->expectsJson()) {
            return redirect()
                ->route('citizen.requests.show', $req->id)
                ->with('success', 'Request submitted successfully.');
        }

        return response()->json($req, Response::HTTP_CREATED);
    }

    // Display a specific request
    public function show(CitizenRequest $request)
    {
        $request->load(['status', 'service.office', 'service.serviceCategory', 'user', 'reviewer', 'documents']);

        $authorization = $this->authorizeRequestAccess($request);
        if ($authorization !== true) {
            return $authorization;
        }

        if (!request()->expectsJson() && $this->getRoleName() === 'citizen') {
            return view('citizen.requests.show', [
                'title' => 'Request Details',
                'requestData' => $request,
            ]);
        }

        return response()->json($request, Response::HTTP_OK);
    }

    // Show the form for editing a request
    public function edit(CitizenRequest $request)
    {
        if ($this->getRoleName() !== 'citizen') {
            return response()->json([
                'message' => 'Only citizens can open request edit view.',
            ], Response::HTTP_FORBIDDEN);
        }

        if ((int) $request->user_id !== (int) Auth::id()) {
            return response()->json([
                'message' => 'You can only edit your own requests.',
            ], Response::HTTP_FORBIDDEN);
        }

        return response()->json($request, Response::HTTP_OK);
    }

    // Update citizen-owned request details
    public function update(Request $requestData, CitizenRequest $request)
    {
        if ($this->getRoleName() !== 'citizen') {
            return response()->json([
                'message' => 'Only citizens can edit request details.',
            ], Response::HTTP_FORBIDDEN);
        }

        if ((int) $request->user_id !== (int) Auth::id()) {
            return response()->json([
                'message' => 'You can only edit your own requests.',
            ], Response::HTTP_FORBIDDEN);
        }

        $currentStatusName = CitizenRequest::normalizeStatusName((string) $request->status?->name);
        if (!in_array($currentStatusName, ['pending', 'missing documents'], true)) {
            return response()->json([
                'message' => 'This request can no longer be edited.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated = $requestData->validate([
            'service_id' => ['sometimes', 'required', 'exists:services,id'],
            'status_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $fieldsToUpdate = [];
        if (array_key_exists('service_id', $validated)) {
            $fieldsToUpdate['service_id'] = $validated['service_id'];
        }
        if (array_key_exists('status_note', $validated)) {
            $fieldsToUpdate['status_note'] = $validated['status_note'];
        }

        if (!empty($fieldsToUpdate)) {
            $request->update($fieldsToUpdate);
        }

        return response()->json($request, Response::HTTP_OK);
    }

    // Update office/admin request status via transition rules
    public function updateStatus(Request $requestData, CitizenRequest $request)
    {
        $roleName = $this->getRoleName();
        if (!in_array($roleName, ['officestaff', 'administrator'], true)) {
            return response()->json([
                'message' => 'Only OfficeStaff or Administrator can update request status.',
            ], Response::HTTP_FORBIDDEN);
        }

        if ($roleName === 'officestaff' && !$this->canOfficeAccessRequest($request)) {
            return response()->json([
                'message' => 'You can only manage requests for your assigned office.',
            ], Response::HTTP_FORBIDDEN);
        }

        $validated = $requestData->validate([
            'status_id' => ['required', 'exists:statuses,id'],
            'status_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $targetStatus = Status::findOrFail($validated['status_id']);
        $fromStatusName = (string) $request->status?->name;
        $toStatusName = (string) $targetStatus->name;

        if (!CitizenRequest::isTransitionAllowed($fromStatusName, $toStatusName)) {
            return response()->json([
                'message' => CitizenRequest::getTransitionErrorMessage($fromStatusName, $toStatusName),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $normalizedTargetStatus = CitizenRequest::normalizeStatusName($toStatusName);
        if (in_array($normalizedTargetStatus, ['missing documents', 'rejected'], true) && empty($validated['status_note'])) {
            return response()->json([
                'message' => 'A reason note is required for Missing Documents or Rejected statuses.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $request->update([
            'status_id' => $targetStatus->id,
            'status_note' => $validated['status_note'] ?? null,
            'reviewed_by' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Request status updated successfully.',
            'data' => $request->fresh(['status', 'service', 'user', 'reviewer']),
        ], Response::HTTP_OK);
    }

    // Delete a request
    public function destroy(CitizenRequest $request)
    {
        if ($this->getRoleName() !== 'citizen') {
            return response()->json([
                'message' => 'Only citizens can delete requests.',
            ], Response::HTTP_FORBIDDEN);
        }

        if ((int) $request->user_id !== (int) Auth::id()) {
            return response()->json([
                'message' => 'You can only delete your own requests.',
            ], Response::HTTP_FORBIDDEN);
        }

        $currentStatusName = CitizenRequest::normalizeStatusName((string) $request->status?->name);
        if ($currentStatusName !== 'pending') {
            return response()->json([
                'message' => 'Only pending requests can be deleted.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $request->delete();

        return response()->json(['message' => 'Request deleted successfully'], Response::HTTP_NO_CONTENT);
    }

    private function getRoleName(): string
    {
        return strtolower((string) Auth::user()?->role?->name);
    }

    private function authorizeRequestAccess(CitizenRequest $request): bool|\Illuminate\Http\JsonResponse
    {
        $roleName = $this->getRoleName();
        $userId = (int) Auth::id();

        if ($roleName === 'administrator') {
            return true;
        }

        if ($roleName === 'citizen') {
            if ((int) $request->user_id === $userId) {
                return true;
            }

            return response()->json([
                'message' => 'You can only access your own requests.',
            ], Response::HTTP_FORBIDDEN);
        }

        if ($roleName === 'officestaff') {
            if ($this->canOfficeAccessRequest($request)) {
                return true;
            }

            return response()->json([
                'message' => 'You can only access requests for your assigned office.',
            ], Response::HTTP_FORBIDDEN);
        }

        return response()->json([
            'message' => 'Unauthorized role for request access.',
        ], Response::HTTP_FORBIDDEN);
    }

    private function canOfficeAccessRequest(CitizenRequest $request): bool
    {
        $officeId = Auth::user()?->office_id;
        if (!$officeId) {
            return false;
        }

        return Service::query()
            ->where('id', $request->service_id)
            ->where('office_id', $officeId)
            ->exists();
    }

    private function generateTrackingNumber(): string
    {
        do {
            $trackingNumber = 'REQ-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);
        } while (CitizenRequest::query()->where('tracking_number', $trackingNumber)->exists());

        return $trackingNumber;
    }
}
