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
                    <p class="mb-0 text-sm"><strong>Service:</strong> {{ $requestData->service?->name ?? '-' }}</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm"><strong>Note:</strong></p>
                    <p class="mt-2 text-sm text-slate-700">{{ $requestData->status_note ?: '-' }}</p>
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
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" rel="noopener" class="btn-base btn-variant-white btn-xs">
                                View File
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
@endsection
