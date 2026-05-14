<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Request Details | GovEase</title>
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <header class="bg-white border-bottom py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('home') }}" class="fw-bold text-decoration-none">GovEase</a>
            <a href="{{ route('citizen.documents.index') }}" class="btn btn-sm btn-outline-primary">Request Another Document</a>
        </div>
    </header>

    <main class="container py-4">
        <div class="mx-auto" style="max-width: 760px;">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h4 fw-bold">Request Details</h1>
                    <p class="text-muted">Tracking number: {{ $request->tracking_number }}</p>

                    <dl class="row">
                        <dt class="col-sm-4">Service</dt>
                        <dd class="col-sm-8">{{ $request->service?->name }}</dd>

                        <dt class="col-sm-4">Office</dt>
                        <dd class="col-sm-8">{{ $request->service?->office?->name ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Municipality</dt>
                        <dd class="col-sm-8">{{ $request->service?->office?->municipality?->name ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Request Status</dt>
                        <dd class="col-sm-8">{{ $request->status?->name ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Payment</dt>
                        <dd class="col-sm-8">
                            @if ($request->payment)
                                <span class="badge text-bg-success">Paid</span>
                                <div class="small text-muted mt-2">
                                    Amount: ${{ number_format($request->payment->amount, 2) }}<br>
                                    Method: {{ $request->payment->payment_method }}<br>
                                    Reference: {{ $request->payment->transaction_reference }}
                                </div>
                            @else
                                <span class="badge text-bg-warning">Unpaid</span>
                            @endif
                        </dd>
                    </dl>

                    @unless ($request->payment)
                        <a href="{{ route('citizen.requests.payment', $request) }}" class="btn btn-primary">Pay Now</a>
                    @endunless
                </div>
            </div>
        </div>
    </main>
</body>
</html>
