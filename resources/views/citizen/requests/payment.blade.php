@extends('layouts.citizen')

@section('title', ($title ?? 'Pay Request') . ' | GovEase')

@section('content')
    <div class="citizen-page">
        <section class="card-padded">
            <div class="card-header">
                <div>
                    <h1 class="citizen-page-title">Pay Request</h1>
                    <p class="citizen-page-subtitle">Complete payment for this document request before it can move forward.</p>
                </div>
                <a href="{{ route('citizen.requests.show', $requestData->id) }}" class="btn-base btn-variant-white">Back to Request</a>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (!$stripeKey)
                <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    Stripe is not configured in this environment yet. Add `STRIPE_KEY` and `STRIPE_SECRET` to continue.
                </div>
            @else
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_380px]">
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-slate-900">Card Payment</h2>

                        <form id="payment-form" class="mt-6 space-y-4">
                            @csrf

                            <div>
                                <label for="cardholder-name" class="mb-2 block text-sm font-medium text-slate-700">Cardholder Name</label>
                                <input
                                    type="text"
                                    id="cardholder-name"
                                    value="{{ trim(($requestData->user?->firstName ?? '') . ' ' . ($requestData->user?->lastName ?? '')) }}"
                                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm"
                                    required
                                >
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700">Card Details</label>
                                <div id="card-element" class="rounded-lg border border-slate-300 px-4 py-3"></div>
                                <p id="card-errors" class="mt-2 text-sm text-red-600"></p>
                            </div>

                            <button id="payment-submit" type="submit" class="btn-base btn-variant-blue">
                                Pay ${{ number_format($amount, 2) }}
                            </button>
                        </form>
                    </div>

                    <aside class="rounded-xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-slate-900">Request Summary</h2>

                        <div class="mt-5 space-y-3 text-sm text-slate-700">
                            <div class="flex items-center justify-between gap-4">
                                <span>Tracking #</span>
                                <span class="font-medium text-slate-900">{{ $requestData->tracking_number }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span>Office</span>
                                <span class="text-right font-medium text-slate-900">{{ $requestData->service?->office?->name ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span>Category</span>
                                <span class="text-right font-medium text-slate-900">{{ $requestData->service?->serviceCategory?->name ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span>Service</span>
                                <span class="text-right font-medium text-slate-900">{{ $requestData->service?->name ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="mt-6 rounded-lg border border-slate-200 bg-white p-4">
                            <div class="flex items-center justify-between text-sm text-slate-600">
                                <span>Document Request Fee</span>
                                <span>${{ number_format($amount, 2) }}</span>
                            </div>
                            <div class="mt-3 border-t border-slate-200 pt-3">
                                <div class="flex items-center justify-between text-base font-semibold text-slate-900">
                                    <span>Total</span>
                                    <span>${{ number_format($amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            @endif
        </section>
    </div>

    @if ($stripeKey)
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            const stripe = Stripe(@json($stripeKey));
            const elements = stripe.elements();
            const cardElement = elements.create('card');
            const cardErrors = document.getElementById('card-errors');
            const paymentForm = document.getElementById('payment-form');
            const paymentSubmit = document.getElementById('payment-submit');
            const cardholderName = document.getElementById('cardholder-name');

            cardElement.mount('#card-element');

            cardElement.on('change', function (event) {
                cardErrors.textContent = event.error ? event.error.message : '';
            });

            paymentForm.addEventListener('submit', async function (event) {
                event.preventDefault();

                paymentSubmit.disabled = true;
                paymentSubmit.textContent = 'Processing...';
                cardErrors.textContent = '';

                try {
                    const intentResponse = await fetch(@json(route('citizen.requests.payment.create-intent', $requestData->id)), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                        },
                    });

                    const intentPayload = await intentResponse.json();
                    if (!intentResponse.ok) {
                        throw new Error(intentPayload.message || 'Unable to start payment.');
                    }

                    const result = await stripe.confirmCardPayment(intentPayload.clientSecret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: cardholderName.value,
                            },
                        },
                    });

                    if (result.error) {
                        throw new Error(result.error.message || 'Payment failed.');
                    }

                    const confirmResponse = await fetch(@json(route('citizen.requests.payment.confirm', $requestData->id)), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            payment_intent_id: result.paymentIntent.id,
                        }),
                    });

                    const confirmPayload = await confirmResponse.json();
                    if (!confirmResponse.ok) {
                        throw new Error(confirmPayload.message || 'Unable to confirm payment.');
                    }

                    window.location.href = confirmPayload.redirect_url;
                } catch (error) {
                    cardErrors.textContent = error.message || 'Payment failed.';
                    paymentSubmit.disabled = false;
                    paymentSubmit.textContent = 'Pay ${{ number_format($amount, 2) }}';
                }
            });
        </script>
    @endif
@endsection
