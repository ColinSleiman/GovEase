@extends('layouts.admin')

@section('title', 'Stripe Payment Test | GovEase Admin')

@section('content')
    <div class="space-y-6">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-bold text-slate-900">Stripe Payment Test</h1>
            <p class="mt-2 text-sm text-slate-600">Test card payments and the hosted Stripe payment link from the admin panel.</p>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5">
                    <h2 class="text-xl font-semibold text-slate-900">Card Element Test</h2>
                    <p class="mt-1 text-sm text-slate-600">Creates a Stripe PaymentIntent for a $25.00 test payment.</p>
                </div>

                @if (! $stripeKey)
                    <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                        STRIPE_KEY is not configured in your local .env file. Add it before testing card payments.
                    </div>
                @endif

                <div class="mb-5 space-y-3 rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <div class="flex justify-between text-sm text-slate-600">
                        <span>Document Request Fee</span>
                        <span>$20.00</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-600">
                        <span>Processing Fee</span>
                        <span>$5.00</span>
                    </div>
                    <div class="flex justify-between border-t border-slate-200 pt-3 text-base font-bold text-slate-900">
                        <span>Total</span>
                        <span id="total-amount" data-amount="2500">$25.00</span>
                    </div>
                </div>

                <form id="payment-form" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Cardholder Name</label>
                        <input id="cardholder-name" type="text" value="Test User" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Card Details</label>
                        <div id="card-element" class="rounded-lg border border-slate-300 bg-white px-4 py-3"></div>
                        <p id="card-errors" class="mt-2 text-sm text-red-600"></p>
                    </div>

                    <x-admin.button variant="blue" type="submit">Pay $25.00</x-admin.button>
                </form>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Hosted Payment Link</h2>
                        <p class="mt-1 text-sm text-slate-600">Uses the test Stripe payment link from the StripeIntegration branch.</p>
                    </div>
                    <span class="rounded-md bg-violet-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-violet-700">Stripe</span>
                </div>

                <div class="mb-5 space-y-3 rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <div class="flex justify-between text-sm text-slate-600">
                        <span>Document Request Fee</span>
                        <span>$5.00</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-600">
                        <span>Processing Fee</span>
                        <span>$0.00</span>
                    </div>
                    <div class="flex justify-between border-t border-slate-200 pt-3 text-base font-bold text-slate-900">
                        <span>Total</span>
                        <span>$5.00</span>
                    </div>
                </div>

                <a href="{{ $paymentLink }}" target="_blank" rel="noopener noreferrer" class="inline-flex w-full items-center justify-center rounded-lg bg-violet-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-violet-700">
                    Pay with Stripe
                </a>

                <p class="mt-3 text-center text-xs text-slate-500">You will be redirected to Stripe's secure checkout page.</p>
            </div>
        </section>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const publishableKey = @json($stripeKey);
        const form = document.getElementById('payment-form');
        const errorBox = document.getElementById('card-errors');

        if (publishableKey && window.Stripe) {
            const stripe = Stripe(publishableKey);
            const elements = stripe.elements();
            const cardElement = elements.create('card');
            cardElement.mount('#card-element');

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                errorBox.textContent = '';

                const amount = Number(document.getElementById('total-amount').dataset.amount);
                const response = await fetch(@json(route('admin.stripe.create-intent')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                    },
                    body: JSON.stringify({ amount }),
                });

                const payload = await response.json();

                if (!response.ok) {
                    errorBox.textContent = payload.message || 'Unable to create a Stripe payment intent.';
                    return;
                }

                const result = await stripe.confirmCardPayment(payload.clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: document.getElementById('cardholder-name').value,
                        },
                    },
                });

                if (result.error) {
                    errorBox.textContent = result.error.message;
                    return;
                }

                if (result.paymentIntent && result.paymentIntent.status === 'succeeded') {
                    window.location.href = @json(route('admin.stripe.success'));
                }
            });
        } else {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                errorBox.textContent = 'Stripe is not configured yet. Add STRIPE_KEY and STRIPE_SECRET to .env.';
            });
        }
    </script>
@endsection
