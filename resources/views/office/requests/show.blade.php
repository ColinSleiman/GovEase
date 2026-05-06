@extends('layouts.office')

@section('title', 'Request Details | GovEase Office')

@php
    $statusName = strtolower((string) $requestData->status?->name);
    $badgeClasses = [
        'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
        'in review' => 'bg-blue-100 text-blue-700 border-blue-200',
        'missing documents' => 'bg-orange-100 text-orange-700 border-orange-200',
        'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        'rejected' => 'bg-red-100 text-red-700 border-red-200',
        'completed' => 'bg-slate-100 text-slate-700 border-slate-200',
    ];
    $badgeClass = $badgeClasses[$statusName] ?? 'bg-slate-100 text-slate-700 border-slate-200';
@endphp

@section('content')
    <div class="admin-page">
        <section class="admin-page-header">
            <div>
                <h1 class="admin-page-title">Request #{{ $requestData->id }}</h1>
                <p class="admin-page-subtitle">Review request details, uploaded documents, and update status.</p>
            </div>
            <x-office.actions.button :href="route('office.requests.index')" variant="white">Back to Requests</x-office.actions.button>
        </section>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="grid gap-6 lg:grid-cols-2">
            <article class="card-padded">
                <h2 class="card-title">Request Summary</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-600">Request ID</dt>
                        <dd class="text-slate-900">#{{ $requestData->id }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-600">Tracking Number</dt>
                        <dd class="text-slate-900">{{ $requestData->tracking_number }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-600">Current Status</dt>
                        <dd>
                            <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">
                                {{ $requestData->status?->name ?? '-' }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-600">Submitted</dt>
                        <dd class="text-slate-900">{{ $requestData->created_at?->format('M d, Y h:i A') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-600">Last Updated</dt>
                        <dd class="text-slate-900">{{ $requestData->updated_at?->format('M d, Y h:i A') }}</dd>
                    </div>
                </dl>
            </article>

            <article class="card-padded">
                <h2 class="card-title">Citizen Information</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-600">Name</dt>
                        <dd class="text-slate-900">{{ $requestData->user?->full_name ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-600">Email</dt>
                        <dd class="text-slate-900">{{ $requestData->user?->email ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-600">Phone</dt>
                        <dd class="text-slate-900">{{ $requestData->user?->phone ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </article>
        </section>

        <section class="card-padded">
            <h2 class="card-title">Service Information</h2>
            <dl class="mt-4 grid gap-4 text-sm md:grid-cols-2">
                <div>
                    <dt class="font-semibold text-slate-600">Office</dt>
                    <dd class="mt-1 text-slate-900">{{ $requestData->service?->office?->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-600">Category</dt>
                    <dd class="mt-1 text-slate-900">{{ $requestData->service?->serviceCategory?->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-600">Service</dt>
                    <dd class="mt-1 text-slate-900">{{ $requestData->service?->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-600">Price</dt>
                    <dd class="mt-1 text-slate-900">{{ $requestData->service?->price ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-600">Duration (Minutes)</dt>
                    <dd class="mt-1 text-slate-900">{{ $requestData->service?->duration ?? '-' }}</dd>
                </div>
            </dl>
        </section>

        <section class="card-padded">
            <h2 class="card-title">Uploaded Documents</h2>
            @if ($requestData->documents->isEmpty())
                <p class="mt-4 text-sm text-slate-600">No uploaded documents.</p>
            @else
                <ul class="mt-4 space-y-2">
                    @foreach ($requestData->documents as $document)
                        <li class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm">
                            <div>
                                <p class="font-medium text-slate-900">{{ $document->document_type }}</p>
                                <p class="text-xs text-slate-500">Uploaded {{ $document->created_at?->format('M d, Y h:i A') }}</p>
                            </div>
                            <a
                                href="{{ asset('storage/' . $document->file_path) }}"
                                target="_blank"
                                rel="noopener"
                                class="btn-base btn-variant-white btn-xs"
                            >
                                View Document
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        <section class="card-padded">
            <h2 class="card-title">Status Management</h2>
            <p class="card-subtitle mt-1">A note is required when rejecting a request or marking documents as missing.</p>

            @if ($allowedStatuses->isEmpty())
                <p class="mt-4 text-sm text-slate-600">No further status transitions allowed for this request.</p>
            @else
                <form action="{{ route('office.requests.update-status', $requestData->id) }}" method="POST" class="mt-4 space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="form-label" for="status_id">Next Status</label>
                            <select id="status_id" name="status_id" class="form-control-base" required>
                                <option value="">Select next status</option>
                                @foreach ($allowedStatuses as $status)
                                    <option value="{{ $status->id }}" @selected((string) old('status_id') === (string) $status->id)>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label" for="status_note">Note / Reason</label>
                            <textarea
                                id="status_note"
                                name="status_note"
                                rows="3"
                                class="form-control-base"
                                placeholder="Provide details for this status update"
                            >{{ old('status_note', $requestData->status_note) }}</textarea>
                        </div>
                    </div>

                    <x-office.actions.button variant="blue" type="submit">Update Status</x-office.actions.button>
                </form>
            @endif
        </section>
    </div>
@endsection
