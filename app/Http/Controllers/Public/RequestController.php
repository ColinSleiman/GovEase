<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentRequest;
use App\Models\Municipality;
use App\Models\Office;
use App\Models\Payment;
use App\Models\Request as CitizenRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class RequestController extends Controller
{
    // Citizen request controller (citizen portal + request creation flow).
    // Display all requests
    public function index()
    {
        $data = CitizenRequest::query()
            ->with(['status', 'service.office', 'service.serviceCategory', 'user', 'reviewer', 'documents', 'payment.status', 'review'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        if (!request()->expectsJson()) {
            return view('citizen.requests.index', [
                'title' => 'My Requests',
                'data' => $data,
            ]);
        }

        return response()->json($data, Response::HTTP_OK);
    }

    // Show the form for creating a new request
    public function create()
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $municipalities = Municipality::query()
            ->orderBy('name')
            ->get(['id', 'name', 'region', 'address', 'latitude', 'longitude']);
        $offices = Office::query()->orderBy('name')->get(['id', 'name', 'municipality_id']);
        $categories = ServiceCategory::query()->orderBy('name')->get(['id', 'name', 'office_id']);
        $services = Service::query()
            ->with(['office:id,name', 'serviceCategory:id,name'])
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'office_id', 'service_category_id']);
        $officesForJs = [];
        foreach ($offices as $office) {
            $officesForJs[] = [
                'id' => $office->id,
                'name' => $office->name,
                'municipality_id' => $office->municipality_id,
            ];
        }

        $municipalitiesForJs = [];
        foreach ($municipalities as $municipality) {
            $municipalitiesForJs[] = [
                'id' => $municipality->id,
                'name' => $municipality->name,
                'region' => $municipality->region,
                'address' => $municipality->address,
                'latitude' => $municipality->latitude,
                'longitude' => $municipality->longitude,
            ];
        }

        $servicesForJs = [];
        foreach ($services as $service) {
            $servicesForJs[] = [
                'id' => $service->id,
                'name' => $service->name,
                'price' => (float) $service->price,
                'office_id' => $service->office_id,
                'service_category_id' => $service->service_category_id,
                'office_name' => $service->office?->name,
                'category_name' => $service->serviceCategory?->name,
            ];
        }

        $categoriesForJs = [];
        foreach ($categories as $category) {
            $categoriesForJs[] = [
                'id' => $category->id,
                'name' => $category->name,
                'office_id' => $category->office_id,
            ];
        }

        if (!request()->expectsJson()) {
            return view('citizen.requests.create', [
                'title' => 'Submit Request',
                'apiKey' => $apiKey,
                'municipalities' => $municipalities,
                'offices' => $offices,
                'categories' => $categories,
                'services' => $services,
                'municipalitiesForJs' => $municipalitiesForJs,
                'officesForJs' => $officesForJs,
                'categoriesForJs' => $categoriesForJs,
                'servicesForJs' => $servicesForJs,
            ]);
        }

        return response()->json([
            'apiKey' => $apiKey,
            'municipalities' => $municipalities,
            'offices' => $offices,
            'categories' => $categories,
            'services' => $services,
            'municipalitiesForJs' => $municipalitiesForJs,
            'officesForJs' => $officesForJs,
            'categoriesForJs' => $categoriesForJs,
            'servicesForJs' => $servicesForJs,
        ], Response::HTTP_OK);
    }

    public function categoriesByOffice(Office $office)
    {
        $categories = ServiceCategory::query()
            ->where('office_id', $office->id)
            ->orderBy('name')
            ->get(['id', 'name', 'office_id']);

        return response()->json($categories, Response::HTTP_OK);
    }

    public function servicesByOfficeAndCategory(Request $request)
    {
        $validated = $request->validate([
            'office_id' => ['required', 'exists:offices,id'],
            'service_category_id' => ['nullable', 'exists:service_categories,id'],
        ]);

        $query = Service::query()
            ->where('office_id', $validated['office_id'])
            ->orderBy('name');

        if (!empty($validated['service_category_id'])) {
            $query->where('service_category_id', $validated['service_category_id']);
        }

        return response()->json(
            $query->get(['id', 'name', 'office_id', 'service_category_id']),
            Response::HTTP_OK
        );
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
            if ((float) $selectedService->price > 0) {
                return redirect()
                    ->route('citizen.requests.payment', $req->id)
                    ->with('success', 'Request submitted. Complete payment to continue processing.');
            }

            return redirect()
                ->route('citizen.requests.show', $req->id)
                ->with('success', 'Request submitted successfully.');
        }

        return response()->json($req, Response::HTTP_CREATED);
    }

    // Display a specific request
    public function show(CitizenRequest $request)
    {
        $request->load(['status', 'service.office', 'service.serviceCategory', 'user', 'reviewer', 'documents', 'payment.status', 'review']);

        if ((int) $request->user_id !== (int) Auth::id()) {
            return response()->json([
                'message' => 'You can only access your own requests.',
            ], Response::HTTP_FORBIDDEN);
        }

        if (!request()->expectsJson()) {
            return view('citizen.requests.show', [
                'title' => 'Request Details',
                'requestData' => $request,
            ]);
        }

        return response()->json($request, Response::HTTP_OK);
    }

    public function payment(CitizenRequest $request)
    {
        $request->load(['status', 'service.office', 'service.serviceCategory', 'payment.status']);

        if ((int) $request->user_id !== (int) Auth::id()) {
            abort(Response::HTTP_FORBIDDEN, 'You can only access your own requests.');
        }

        $amount = (float) ($request->service?->price ?? 0);
        if ($amount <= 0) {
            return redirect()
                ->route('citizen.requests.show', $request->id)
                ->with('success', 'This request does not require payment.');
        }

        $isPaid = $request->payment && strtolower((string) $request->payment->status) === 'completed';
        if ($isPaid) {
            return redirect()
                ->route('citizen.requests.show', $request->id)
                ->with('success', 'This request has already been paid.');
        }

        return view('citizen.requests.payment', [
            'title' => 'Pay Request',
            'requestData' => $request,
            'stripeKey' => env('STRIPE_KEY'),
            'amount' => $amount,
            'amountInCents' => (int) round($amount * 100),
        ]);
    }

    public function createPaymentIntent(CitizenRequest $request)
    {
        $request->load(['service', 'payment']);

        if ((int) $request->user_id !== (int) Auth::id()) {
            return response()->json([
                'message' => 'You can only pay for your own requests.',
            ], Response::HTTP_FORBIDDEN);
        }

        if (empty(env('STRIPE_SECRET'))) {
            return response()->json([
                'message' => 'Stripe is not configured on this environment.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $amount = (float) ($request->service?->price ?? 0);
        if ($amount <= 0) {
            return response()->json([
                'message' => 'This request does not require payment.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->payment && strtolower((string) $request->payment->status) === 'completed') {
            return response()->json([
                'message' => 'This request has already been paid.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $intent = PaymentIntent::create([
            'amount' => (int) round($amount * 100),
            'currency' => 'usd',
            'metadata' => [
                'request_id' => (string) $request->id,
                'tracking_number' => (string) $request->tracking_number,
                'user_id' => (string) Auth::id(),
            ],
        ]);

        return response()->json([
            'clientSecret' => $intent->client_secret,
        ], Response::HTTP_OK);
    }

    public function confirmPayment(Request $paymentRequest, CitizenRequest $request)
    {
        $request->load(['service', 'payment']);

        if ((int) $request->user_id !== (int) Auth::id()) {
            return response()->json([
                'message' => 'You can only pay for your own requests.',
            ], Response::HTTP_FORBIDDEN);
        }

        $validated = $paymentRequest->validate([
            'payment_intent_id' => ['required', 'string'],
        ]);

        if (empty(env('STRIPE_SECRET'))) {
            return response()->json([
                'message' => 'Stripe is not configured on this environment.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $intent = PaymentIntent::retrieve($validated['payment_intent_id']);

        if (($intent->metadata['request_id'] ?? null) !== (string) $request->id) {
            return response()->json([
                'message' => 'This payment does not belong to the selected request.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($intent->status !== 'succeeded') {
            return response()->json([
                'message' => 'Payment has not been completed yet.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $completedStatus = Status::query()->where('name', 'Completed')->firstOrFail();

        Payment::updateOrCreate(
            ['request_id' => $request->id],
            [
                'amount' => (float) ($request->service?->price ?? 0),
                'payment_method' => 'Stripe',
                'status' => 'Completed',
                'status_id' => $completedStatus->id,
                'transaction_reference' => $intent->id,
            ]
        );

        return response()->json([
            'redirect_url' => route('citizen.requests.show', $request->id),
        ], Response::HTTP_OK);
    }

    // Show the form for editing a request
    public function edit(CitizenRequest $request)
    {
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

    // Delete a request
    public function destroy(CitizenRequest $request)
    {
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

    private function generateTrackingNumber(): string
    {
        do {
            $trackingNumber = 'REQ-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);
        } while (CitizenRequest::query()->where('tracking_number', $trackingNumber)->exists());

        return $trackingNumber;
    }
}
