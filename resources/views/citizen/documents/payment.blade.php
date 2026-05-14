<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pay Request | GovEase</title>
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <header class="bg-white border-bottom py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('home') }}" class="fw-bold text-decoration-none">GovEase</a>
            <a href="{{ route('citizen.documents.index') }}" class="btn btn-sm btn-outline-secondary">Back to Documents</a>
        </div>
    </header>

    <main class="container py-4">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h4 fw-bold">Payment Required</h1>
                        <p class="text-muted mb-4">Pay the document request fee so the municipality can start processing it.</p>

                        <dl class="row small">
                            <dt class="col-5">Tracking</dt>
                            <dd class="col-7">{{ $request->tracking_number }}</dd>
                            <dt class="col-5">Service</dt>
                            <dd class="col-7">{{ $request->service?->name }}</dd>
                            <dt class="col-5">Office</dt>
                            <dd class="col-7">{{ $request->service?->office?->name ?? 'N/A' }}</dd>
                            <dt class="col-5">Status</dt>
                            <dd class="col-7">{{ $request->status?->name ?? 'N/A' }}</dd>
                        </dl>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <span class="fw-semibold">Total</span>
                            <strong id="total-amount" data-amount="{{ (int) round($request->service->price * 100) }}" class="fs-4">
                                ${{ number_format($request->service->price, 2) }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="h5 fw-bold">Card Payment</h2>
                        <p class="text-muted small">Use Stripe test card <strong>4242 4242 4242 4242</strong>, any future date, and any 3-digit CVC.</p>

                        @if ($request->payment)
                            <div class="alert alert-success">
                                This request is already paid.
                                <a href="{{ route('citizen.requests.show', $request) }}">View request</a>.
                            </div>
                        @else
                            @if (! $stripeKey)
                                <div class="alert alert-warning">STRIPE_KEY is not configured in your .env file.</div>
                            @endif

                            <form id="payment-form" class="vstack gap-3">
                                @csrf
                                <div>
                                    <label class="form-label">Cardholder Name</label>
                                    <input id="cardholder-name" type="text" value="{{ Auth::user()?->name ?? 'Test User' }}" class="form-control">
                                </div>
                                <div>
                                    <label class="form-label">Card Details</label>
                                    <div id="card-element" class="form-control py-3"></div>
                                    <div id="card-errors" class="form-text text-danger"></div>
                                </div>
                                <button type="submit" class="btn btn-primary">Pay ${{ number_format($request->service->price, 2) }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    @if (! $request->payment)
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

                    const intentResponse = await fetch(@json(route('citizen.requests.payment-intent', $request)), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': @json(csrf_token()),
                        },
                    });

                    const intentPayload = await intentResponse.json();

                    if (!intentResponse.ok) {
                        errorBox.textContent = intentPayload.message || 'Unable to start payment.';
                        return;
                    }

                    const result = await stripe.confirmCardPayment(intentPayload.clientSecret, {
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

                    const confirmResponse = await fetch(@json(route('citizen.requests.confirm-payment', $request)), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': @json(csrf_token()),
                        },
                        body: JSON.stringify({ payment_intent_id: result.paymentIntent.id }),
                    });

                    const confirmPayload = await confirmResponse.json();

                    if (!confirmResponse.ok) {
                        errorBox.textContent = confirmPayload.message || 'Payment succeeded, but the request could not be updated.';
                        return;
                    }

                    window.location.href = confirmPayload.redirectUrl;
                });
            } else if (form) {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    errorBox.textContent = 'Stripe is not configured yet. Add STRIPE_KEY and STRIPE_SECRET to .env.';
                });
            }
        </script>
    @endif
</body>
</html>
