@extends('layouts.admin')

@section('title', 'Incoming Requests | GovEase Admin')

@php
    $badgeClasses = [
        'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
        'in review' => 'bg-blue-100 text-blue-700 border-blue-200',
        'missing documents' => 'bg-orange-100 text-orange-700 border-orange-200',
        'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        'rejected' => 'bg-red-100 text-red-700 border-red-200',
        'completed' => 'bg-slate-100 text-slate-700 border-slate-200',
    ];
@endphp

@section('content')
    <div class="admin-page">
        <section class="admin-page-header">
            <div>
                <h1 class="admin-page-title">Incoming Requests</h1>
                <p class="admin-page-subtitle">Monitor and manage citizen service requests across all offices.</p>
            </div>
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

        <section class="card-padded">
            <form method="GET" action="{{ route('admin.requests.index') }}" class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="form-label">Search (Citizen / Request ID / Tracking #)</label>
                    <input type="text" name="search" value="{{ $filters['search'] }}" class="form-control-base" placeholder="Search requests">
                </div>

                <div>
                    <label class="form-label">Office</label>
                    <select name="office_id" class="form-control-base">
                        <option value="">All offices</option>
                        @foreach ($offices as $office)
                            <option value="{{ $office->id }}" @selected((string) $filters['office_id'] === (string) $office->id)>{{ $office->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Status</label>
                    <select name="status_id" class="form-control-base">
                        <option value="">All statuses</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}" @selected((string) $filters['status_id'] === (string) $status->id)>{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Service Category</label>
                    <select name="service_category_id" class="form-control-base">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $filters['service_category_id'] === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Service</label>
                    <select name="service_id" class="form-control-base">
                        <option value="">All services</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected((string) $filters['service_id'] === (string) $service->id)>{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" value="{{ $filters['date_from'] }}" class="form-control-base">
                    </div>
                    <div>
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" value="{{ $filters['date_to'] }}" class="form-control-base">
                    </div>
                </div>

                <div class="md:col-span-3 flex flex-wrap gap-3">
                    <x-admin.actions.button variant="blue" type="submit">Apply Filters</x-admin.actions.button>
                    <x-admin.actions.button :href="route('admin.requests.index')" variant="white">Reset</x-admin.actions.button>
                </div>
            </form>
        </section>

        <section class="admin-table-wrap">
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead class="admin-table-head">
                        <tr>
                            <th class="admin-table-th">Tracking #</th>
                            <th class="admin-table-th">Citizen</th>
                            <th class="admin-table-th">Office</th>
                            <th class="admin-table-th">Service</th>
                            <th class="admin-table-th">Status</th>
                            <th class="admin-table-th">Payment</th>
                            <th class="admin-table-th">Submitted</th>
                            <th class="admin-table-th-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admin-table-body">
                        @forelse ($rows as $row)
                            @php
                                $normalizedStatus = strtolower((string) $row->status?->name);
                                $badgeClass = $badgeClasses[$normalizedStatus] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            @endphp
                            <tr class="admin-table-row">
                                <td class="admin-table-td">{{ $row->tracking_number }}</td>
                                <td class="admin-table-td">{{ $row->user?->full_name ?? '-' }}</td>
                                <td class="admin-table-td">{{ $row->service?->office?->name ?? '-' }}</td>
                                <td class="admin-table-td">{{ $row->service?->name ?? '-' }}</td>
                                <td class="admin-table-td">
                                    <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">
                                        {{ $row->status?->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="admin-table-td">{{ $row->payment?->status ?? 'Pending Payment' }}</td>
                                <td class="admin-table-td">{{ $row->created_at?->format('M d, Y h:i A') }}</td>
                                <td class="admin-table-actions-cell">
                                    <div class="admin-actions">
                                        <x-admin.actions.button :href="route('admin.requests.show', $row->id)" variant="white" class="btn-xs">View</x-admin.actions.button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="admin-empty">No requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="card-padded">
            {{ $rows->links() }}
        </div>
    </div>
@endsection
