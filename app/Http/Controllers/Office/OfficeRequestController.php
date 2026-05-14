<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\Request as ServiceRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Status;
use App\Services\RequestPdfGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfficeRequestController extends Controller
{
    public function __construct(private readonly RequestPdfGenerator $pdfGenerator)
    {
    }
    // Office request management controller (Blade pages + office-only scope).
    public function index(Request $request)
    {
        $officeId = Auth::user()?->office_id;
        abort_if(!$officeId, 403);

        $query = ServiceRequest::query()
            ->with(['user', 'service.office', 'service.serviceCategory', 'status'])
            ->whereHas('service', function ($serviceQuery) use ($officeId) {
                $serviceQuery->where('office_id', $officeId);
            });

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->integer('status_id'));
        }

        if ($request->filled('service_category_id')) {
            $serviceCategoryId = $request->integer('service_category_id');
            $query->whereHas('service', function ($serviceQuery) use ($serviceCategoryId) {
                $serviceQuery->where('service_category_id', $serviceCategoryId);
            });
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->integer('service_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));

            $query->where(function ($searchQuery) use ($search) {
                $searchQuery
                    ->where('tracking_number', 'like', '%' . $search . '%')
                    ->orWhere('id', $search)
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->whereRaw("CONCAT(firstName, ' ', lastName) like ?", ['%' . $search . '%']);
                    });
            });
        }

        $rows = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('office.requests.index', [
            'rows' => $rows,
            'statuses' => Status::query()->orderBy('name')->get(['id', 'name']),
            'categories' => ServiceCategory::query()
                ->where('office_id', $officeId)
                ->orderBy('name')
                ->get(['id', 'name']),
            'services' => Service::query()
                ->where('office_id', $officeId)
                ->orderBy('name')
                ->get(['id', 'name']),
            'filters' => [
                'status_id' => $request->input('status_id'),
                'service_category_id' => $request->input('service_category_id'),
                'service_id' => $request->input('service_id'),
                'search' => $request->input('search'),
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to'),
            ],
        ]);
    }

    public function show(ServiceRequest $request)
    {
        $request->load(['user', 'service.office', 'service.serviceCategory', 'status', 'documents', 'reviewer']);
        $this->authorizeOfficeRequest($request);

        $allowedStatuses = $this->allowedNextStatuses($request);

        return view('office.requests.show', [
            'requestData' => $request,
            'allowedStatuses' => $allowedStatuses,
        ]);
    }

    public function updateStatus(Request $httpRequest, ServiceRequest $request)
    {
        $request->loadMissing(['status', 'service']);
        $this->authorizeOfficeRequest($request);

        $validated = $httpRequest->validate([
            'status_id' => ['required', 'exists:statuses,id'],
            'status_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $targetStatus = Status::query()->findOrFail($validated['status_id']);
        $targetStatusName = ServiceRequest::normalizeStatusName((string) $targetStatus->name);

        $allowedStatusNames = $this->allowedNextStatusNames($request);
        if (!in_array($targetStatusName, $allowedStatusNames, true)) {
            return back()->with('error', 'Invalid status transition for this request.');
        }

        if (
            in_array($targetStatusName, ['rejected', 'missing documents'], true)
            && blank($validated['status_note'] ?? null)
        ) {
            return back()
                ->withErrors([
                    'status_note' => 'A note is required when rejecting a request or marking documents as missing.',
                ])
                ->withInput();
        }

        $request->update([
            'status_id' => $targetStatus->id,
            'status_note' => $validated['status_note'] ?? null,
            'reviewed_by' => Auth::id(),
        ]);

        $request->load(['status', 'payment', 'service.office.municipality', 'user', 'reviewer']);
        $this->pdfGenerator->generateForCompletedPaidRequest($request);

        return back()->with('success', 'Request status updated successfully.');
    }

    private function authorizeOfficeRequest(ServiceRequest $request): void
    {
        $officeId = Auth::user()?->office_id;
        $requestOfficeId = $request->service?->office_id;

        abort_if(!$officeId || (int) $requestOfficeId !== (int) $officeId, 403);
    }

    private function allowedNextStatuses(ServiceRequest $request)
    {
        $allowedStatusNames = $this->allowedNextStatusNames($request);
        if (empty($allowedStatusNames)) {
            return collect();
        }

        $statuses = Status::query()->orderBy('name')->get(['id', 'name']);

        return $statuses->filter(function ($status) use ($allowedStatusNames) {
            return in_array(ServiceRequest::normalizeStatusName($status->name), $allowedStatusNames, true);
        })->values();
    }

    private function allowedNextStatusNames(ServiceRequest $request): array
    {
        $currentStatusName = ServiceRequest::normalizeStatusName((string) $request->status?->name);
        $allowedTransitions = ServiceRequest::getAllowedTransitions();

        return $allowedTransitions[$currentStatusName] ?? [];
    }
}
