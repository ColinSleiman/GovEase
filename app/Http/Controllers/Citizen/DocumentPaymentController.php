<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Request as ServiceRequest;
use App\Models\Service;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DocumentPaymentController extends Controller
{
    public function services()
    {
        $services = Service::with(['office.municipality', 'serviceCategory'])
            ->orderBy('name')
            ->get();

        return view('citizen.documents.index', compact('services'));
    }

    public function storeRequest(Service $service)
    {
        if (! Auth::check()) {
            return redirect()->route('home')->with('error', 'Please log in before requesting a document.');
        }

        $pendingPaymentStatus = Status::firstOrCreate(['name' => 'Pending Payment']);

        $request = ServiceRequest::create([
            'qr_code' => null,
            'tracking_number' => 'GOV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),
            'service_id' => $service->id,
            'status_id' => $pendingPaymentStatus->id,
            'appointment_id' => null,
        ]);

        $request->users()->syncWithoutDetaching([Auth::id()]);

        return redirect()->route('citizen.requests.payment', $request);
    }

    public function show(ServiceRequest $request)
    {
        $this->authorizeCitizenRequest($request);

        return view('citizen.documents.show', [
            'request' => $request->load(['status', 'service.office.municipality', 'payment.status']),
        ]);
    }

    public function payment(ServiceRequest $request)
    {
        $this->authorizeCitizenRequest($request);

        return view('citizen.documents.payment', [
            'request' => $request->load(['status', 'service.office.municipality', 'payment.status']),
            'stripeKey' => env('STRIPE_KEY'),
        ]);
    }

    public function createIntent(ServiceRequest $request)
    {
        $this->authorizeCitizenRequest($request);
        $request->load('service');

        if ($request->payment) {
            return response()->json(['message' => 'This request is already paid.'], 422);
        }

        if (! env('STRIPE_SECRET')) {
            return response()->json(['message' => 'STRIPE_SECRET is not configured.'], 422);
        }

        if (! class_exists(\Stripe\Stripe::class) || ! class_exists(\Stripe\PaymentIntent::class)) {
            return response()->json(['message' => 'stripe/stripe-php is not installed. Run composer install.'], 422);
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $intent = \Stripe\PaymentIntent::create([
            'amount' => $this->amountInCents($request),
            'currency' => 'usd',
            'metadata' => [
                'request_id' => (string) $request->id,
                'tracking_number' => $request->tracking_number,
            ],
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        return response()->json(['clientSecret' => $intent->client_secret]);
    }

    public function confirmPayment(Request $httpRequest, ServiceRequest $request)
    {
        $this->authorizeCitizenRequest($request);
        $request->load('service');

        $validated = $httpRequest->validate([
            'payment_intent_id' => ['required', 'string'],
        ]);

        if (! env('STRIPE_SECRET')) {
            return response()->json(['message' => 'STRIPE_SECRET is not configured.'], 422);
        }

        if (! class_exists(\Stripe\Stripe::class) || ! class_exists(\Stripe\PaymentIntent::class)) {
            return response()->json(['message' => 'stripe/stripe-php is not installed. Run composer install.'], 422);
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $intent = \Stripe\PaymentIntent::retrieve($validated['payment_intent_id']);

        if ($intent->status !== 'succeeded') {
            return response()->json(['message' => 'Stripe payment has not succeeded yet.'], 422);
        }

        if ((int) $intent->amount !== $this->amountInCents($request)) {
            return response()->json(['message' => 'Stripe amount does not match this request.'], 422);
        }

        $paidStatus = Status::firstOrCreate(['name' => 'Paid']);
        $pendingStatus = Status::firstOrCreate(['name' => 'Pending']);

        Payment::updateOrCreate(
            ['transaction_reference' => $intent->id],
            [
                'amount' => $request->service->price,
                'payment_method' => 'Stripe',
                'status_id' => $paidStatus->id,
                'request_id' => $request->id,
            ]
        );

        $request->update(['status_id' => $pendingStatus->id]);

        return response()->json([
            'redirectUrl' => route('citizen.requests.show', $request),
        ]);
    }

    private function amountInCents(ServiceRequest $request): int
    {
        return (int) round(((float) $request->service->price) * 100);
    }

    private function authorizeCitizenRequest(ServiceRequest $request): void
    {
        if (! Auth::check() || ! $request->users()->whereKey(Auth::id())->exists()) {
            abort(403);
        }
    }
}
