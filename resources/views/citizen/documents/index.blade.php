<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Request Documents | GovEase</title>
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <header class="bg-white border-bottom py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('home') }}" class="fw-bold text-decoration-none">GovEase</a>
            <div class="d-flex gap-2">
                <a href="{{ route('citizen.dashboard') }}" class="btn btn-sm btn-outline-primary">Citizen Dashboard</a>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">Home</a>
            </div>
        </div>
    </header>

    <main class="container py-4">
        <div class="mb-4">
            <h1 class="h3 fw-bold">Request Documents</h1>
            <p class="text-muted">Choose a document service, review the price, then pay securely before the request is processed.</p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-3">
            @forelse ($services as $service)
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <div class="mb-3">
                                <span class="badge text-bg-primary">{{ $service->serviceCategory?->name ?? 'Document Service' }}</span>
                            </div>
                            <h2 class="h5 fw-bold">{{ $service->name }}</h2>
                            <p class="text-muted flex-grow-1">{{ $service->description }}</p>

                            <dl class="small text-muted mb-3">
                                <dt>Office</dt>
                                <dd>{{ $service->office?->name ?? 'N/A' }}</dd>
                                <dt>Municipality</dt>
                                <dd>{{ $service->office?->municipality?->name ?? 'N/A' }}</dd>
                                <dt>Duration</dt>
                                <dd>{{ $service->duration }} minutes</dd>
                            </dl>

                            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                <strong class="fs-5">${{ number_format($service->price, 2) }}</strong>
                                <form action="{{ route('citizen.documents.request', $service) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Request & Pay</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">
                        No priced services are available yet. Create services from the admin area first.
                    </div>
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
