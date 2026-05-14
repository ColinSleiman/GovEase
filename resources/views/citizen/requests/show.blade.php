@extends('layouts.citizen')

@section('title', ($title ?? 'Request Details') . ' | GovEase')

@section('content')
    <div class="citizen-page">
        <section class="card-padded">
            <div class="card-header">
                <div>
                    <h1 class="citizen-page-title">Request Details</h1>
                    <p class="citizen-page-subtitle">Track current status and view submitted documents.</p>
                </div>
                <a href="{{ route('citizen.requests.index') }}" class="btn-base btn-variant-white">Back to My Requests</a>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <p class="mb-2 text-sm"><strong>Tracking #:</strong> {{ $requestData->tracking_number }}</p>
                    <p class="mb-2 text-sm"><strong>Status:</strong> {{ $requestData->status?->name ?? '-' }}</p>
                    <p class="mb-2 text-sm"><strong>Office:</strong> {{ $requestData->service?->office?->name ?? '-' }}</p>
                    <p class="mb-2 text-sm"><strong>Category:</strong> {{ $requestData->service?->serviceCategory?->name ?? '-' }}</p>
                    <p class="mb-2 text-sm"><strong>Service:</strong> {{ $requestData->service?->name ?? '-' }}</p>
                    <p class="mb-0 text-sm"><strong>Service Fee:</strong> ${{ number_format((float) ($requestData->service?->price ?? 0), 2) }}</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm"><strong>Note:</strong></p>
                    <p class="mt-2 text-sm text-slate-700">{{ $requestData->status_note ?: '-' }}</p>
                </div>
            </div>
        </section>

        <section class="card-padded">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Payment</h2>
                    <p class="card-subtitle">Citizens must complete payment before the document request can move forward.</p>
                </div>
                @if ((float) ($requestData->service?->price ?? 0) > 0 && strtolower((string) ($requestData->payment?->status ?? '')) !== 'completed')
                    <a href="{{ route('citizen.requests.payment', $requestData->id) }}" class="btn-base btn-variant-blue">Pay Now</a>
                @endif
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Amount</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">${{ number_format((float) ($requestData->service?->price ?? 0), 2) }}</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Payment Status</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">{{ $requestData->payment?->status ?? (((float) ($requestData->service?->price ?? 0) > 0) ? 'Pending Payment' : 'Not Required') }}</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Reference</p>
                    <p class="mt-2 break-all text-sm text-slate-700">{{ $requestData->payment?->transaction_reference ?? '-' }}</p>
                </div>
            </div>
        </section>

        <section class="card-padded">
            <h2 class="card-title mb-4">Uploaded Documents</h2>
            @if ($requestData->documents->isEmpty())
                <p class="text-sm text-slate-600">No documents uploaded.</p>
            @else
                <ul class="space-y-2">
                    @foreach ($requestData->documents as $document)
                        <li class="flex items-center justify-between rounded-lg border border-slate-200 px-4 py-3 text-sm">
                            <span>{{ $document->document_type }}</span>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('documents.preview', $document->id) }}" target="_blank" rel="noopener" class="btn-base btn-variant-white btn-xs">
                                    View File
                                </a>
                                <a href="{{ route('documents.download', $document->id) }}" class="btn-base btn-variant-blue btn-xs">
                                    Download PDF
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        @if (strtolower((string) ($requestData->status?->name ?? '')) === 'completed')
            <section class="card-padded">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">Feedback</h2>
                        <p class="card-subtitle">Rate and review the office service after completion.</p>
                    </div>
                    @if ($requestData->review)
                        <a href="{{ route('citizen.reviews.edit', $requestData->review->id) }}" class="btn-base btn-variant-white">Edit Review</a>
                    @else
                        <a href="{{ route('citizen.reviews.create', $requestData->id) }}" class="btn-base btn-variant-blue">Leave Review</a>
                    @endif
                </div>

                @if ($requestData->review)
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm"><strong>Rating:</strong> {{ $requestData->review->rating }}/5</p>
                        <p class="mt-2 text-sm text-slate-700">{{ $requestData->review->comment }}</p>
                    </div>
                @else
                    <p class="text-sm text-slate-600">This completed request is ready for your feedback.</p>
                @endif
            </section>
        @endif
    </div>
@endsection
