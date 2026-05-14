<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\Office;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Request as ServiceRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Status;
use App\Models\User;
use App\Services\RequestPdfGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class AdminController extends Controller
{
    public function __construct(private readonly RequestPdfGenerator $pdfGenerator)
    {
    }
    public function index(){ return redirect()->route('admin.dashboard'); }
    
    public function dashboard()
    {
        $offices = Office::withCount('services')->latest()->take(5)->get();
        $municipalities = Municipality::latest()->take(5)->get();
        $users = User::latest()->take(5)->get();
        $requests = ServiceRequest::query()
            ->with(['user', 'service.office', 'status'])
            ->latest()
            ->take(5)
            ->get();
        $serviceCount = Service::count();
        $requestCount = ServiceRequest::count();
        $pendingRequestCount = ServiceRequest::query()
            ->whereHas('status', fn ($query) => $query->where('name', 'Pending'))
            ->count();
        $revenueTotal = (float) Payment::query()
            ->where('status', 'Completed')
            ->sum('amount');

        return view('admin.dashboard.index', compact(
            'offices',
            'municipalities',
            'users',
            'requests',
            'serviceCount',
            'requestCount',
            'pendingRequestCount',
            'revenueTotal'
        ));
    }

    public function requests(Request $request)
    {
        $query = ServiceRequest::query()
            ->with(['user', 'service.office.municipality', 'service.serviceCategory', 'status', 'payment'])
            ->latest();

        if ($request->filled('office_id')) {
            $officeId = $request->integer('office_id');
            $query->whereHas('service', fn ($serviceQuery) => $serviceQuery->where('office_id', $officeId));
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->integer('status_id'));
        }

        if ($request->filled('service_category_id')) {
            $serviceCategoryId = $request->integer('service_category_id');
            $query->whereHas('service', fn ($serviceQuery) => $serviceQuery->where('service_category_id', $serviceCategoryId));
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

        $rows = $query->paginate(10)->withQueryString();

        return view('admin.requests.index', [
            'rows' => $rows,
            'statuses' => Status::query()->orderBy('name')->get(['id', 'name']),
            'offices' => Office::query()->orderBy('name')->get(['id', 'name']),
            'categories' => ServiceCategory::query()->orderBy('name')->get(['id', 'name']),
            'services' => Service::query()->orderBy('name')->get(['id', 'name']),
            'filters' => [
                'office_id' => $request->input('office_id'),
                'status_id' => $request->input('status_id'),
                'service_category_id' => $request->input('service_category_id'),
                'service_id' => $request->input('service_id'),
                'search' => $request->input('search'),
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to'),
            ],
        ]);
    }

    public function requestShow(ServiceRequest $request)
    {
        $request->load(['user', 'service.office.municipality', 'service.serviceCategory', 'status', 'documents', 'reviewer', 'payment']);

        return view('admin.requests.show', [
            'requestData' => $request,
            'allowedStatuses' => $this->allowedNextStatuses($request),
        ]);
    }

    public function updateRequestStatus(Request $httpRequest, ServiceRequest $request)
    {
        $request->loadMissing(['status', 'service']);

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

    public function servicesMonitor(Request $request)
    {
        $query = Service::query()
            ->with(['office.municipality', 'serviceCategory'])
            ->withCount('requests');

        if ($request->filled('office_id')) {
            $query->where('office_id', $request->integer('office_id'));
        }

        if ($request->filled('service_category_id')) {
            $query->where('service_category_id', $request->integer('service_category_id'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where('name', 'like', '%' . $search . '%');
        }

        $rows = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString()
            ->through(function ($service) {
                $pendingCount = $service->requests()->whereHas('status', fn ($query) => $query->where('name', 'Pending'))->count();
                $inReviewCount = $service->requests()->whereHas('status', fn ($query) => $query->where('name', 'In Review'))->count();
                $completedCount = $service->requests()->whereHas('status', fn ($query) => $query->where('name', 'Completed'))->count();
                $service->pending_requests_count = $pendingCount;
                $service->in_review_requests_count = $inReviewCount;
                $service->completed_requests_count = $completedCount;

                return $service;
            });

        return view('admin.services.monitor', [
            'rows' => $rows,
            'offices' => Office::query()->orderBy('name')->get(['id', 'name']),
            'categories' => ServiceCategory::query()->orderBy('name')->get(['id', 'name']),
            'filters' => [
                'office_id' => $request->input('office_id'),
                'service_category_id' => $request->input('service_category_id'),
                'search' => $request->input('search'),
            ],
        ]);
    }

    public function reportsOfficeRequests(Request $request)
    {
        $rows = Office::query()
            ->with('municipality')
            ->withCount(['requests'])
            ->when($request->filled('municipality_id'), function ($query) use ($request) {
                $query->where('municipality_id', $request->integer('municipality_id'));
            })
            ->orderByDesc('requests_count')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.reports.office-requests', [
            'rows' => $rows,
            'municipalities' => Municipality::query()->orderBy('name')->get(['id', 'name']),
            'totalRequests' => ServiceRequest::count(),
            'filters' => [
                'municipality_id' => $request->input('municipality_id'),
            ],
        ]);
    }

    public function reportsRevenue(Request $request)
    {
        $paymentsQuery = Payment::query()
            ->where('status', 'Completed')
            ->whereHas('request.service.office')
            ->with(['request.service.office.municipality']);

        if ($request->filled('office_id')) {
            $officeId = $request->integer('office_id');
            $paymentsQuery->whereHas('request.service', fn ($serviceQuery) => $serviceQuery->where('office_id', $officeId));
        }

        $payments = $paymentsQuery
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $officeRevenue = Office::query()
            ->with('municipality')
            ->when($request->filled('office_id'), function ($query) use ($request) {
                $query->where('id', $request->integer('office_id'));
            })
            ->withSum(['requests as revenue_total' => function ($requestQuery) {
                $requestQuery->join('payments', 'payments.request_id', '=', 'requests.id')
                    ->where('payments.status', 'Completed');
            }], 'payments.amount')
            ->get();

        return view('admin.reports.revenue', [
            'payments' => $payments,
            'officeRevenue' => $officeRevenue,
            'offices' => Office::query()->orderBy('name')->get(['id', 'name']),
            'summaryRevenue' => (float) Payment::query()->where('status', 'Completed')->sum('amount'),
            'filters' => [
                'office_id' => $request->input('office_id'),
            ],
        ]);
    }

    public function reviews(Request $request)
    {
        $query = Review::query()
            ->with(['user', 'office.municipality', 'service.serviceCategory', 'request'])
            ->latest();

        if ($request->filled('office_id')) {
            $query->where('office_id', $request->integer('office_id'));
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->integer('service_id'));
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->integer('rating'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery
                    ->where('comment', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->whereRaw("CONCAT(firstName, ' ', lastName) like ?", ['%' . $search . '%'])
                            ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('service', function ($serviceQuery) use ($search) {
                        $serviceQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('office', function ($officeQuery) use ($search) {
                        $officeQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $rows = $query->paginate(10)->withQueryString();

        return view('admin.reviews.index', [
            'rows' => $rows,
            'offices' => Office::query()->orderBy('name')->get(['id', 'name']),
            'services' => Service::query()->orderBy('name')->get(['id', 'name']),
            'filters' => [
                'office_id' => $request->input('office_id'),
                'service_id' => $request->input('service_id'),
                'rating' => $request->input('rating'),
                'search' => $request->input('search'),
            ],
        ]);
    }
    public function services(){ return redirect()->route('admin.dashboard'); }
    public function settings(){ return redirect()->route('admin.dashboard'); }
    public function reports(){ return redirect()->route('admin.dashboard'); }
    public function notifications(){ return redirect()->route('admin.dashboard'); }
    public function logs(){ return redirect()->route('admin.dashboard'); }
    public function help(){ return redirect()->route('admin.dashboard'); }
    public function stripeTest(){ return view('admin.stripe.index'); }
    public function stripeSuccess(){ return view('admin.stripe.success'); }

    public function createIntent(Request $request) {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $amount = (int) $request->input('amount', 2500); // cents, fallback $25.00

        $intent = PaymentIntent::create([
            'amount'   => $amount,
            'currency' => 'usd',
        ]);

        return response()->json(['clientSecret' => $intent->client_secret]);
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
