@extends('layouts.citizen')

@section('title', 'Citizen Dashboard | GovEase')

@section('content')
    <div class="citizen-page">
        <section class="card-padded">
            <h1 class="citizen-page-title">Citizen Dashboard</h1>
            <p class="citizen-page-subtitle">
                Submit new requests, track your existing requests, and monitor your service progress.
            </p>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('citizen.requests.create') }}" class="btn-base btn-variant-blue">Create Request</a>
                <a href="{{ route('citizen.requests.index') }}" class="btn-base btn-variant-white">View My Requests</a>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="card-padded">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Requests</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ $totalRequests }}</p>
            </article>
            <article class="card-padded">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pending</p>
                <p class="mt-2 text-2xl font-bold text-amber-600">{{ $pendingCount }}</p>
            </article>
            <article class="card-padded">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">In Review</p>
                <p class="mt-2 text-2xl font-bold text-blue-600">{{ $inReviewCount }}</p>
            </article>
            <article class="card-padded">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Completed</p>
                <p class="mt-2 text-2xl font-bold text-emerald-600">{{ $completedCount }}</p>
            </article>
        </section>

        <section class="card-padded">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Latest Requests</h2>
                    <p class="card-subtitle">Your five most recent service requests.</p>
                </div>
            </div>
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead class="admin-table-head">
                    <tr>
                        <th class="admin-table-th">Tracking #</th>
                        <th class="admin-table-th">Office</th>
                        <th class="admin-table-th">Service</th>
                        <th class="admin-table-th">Status</th>
                        <th class="admin-table-th-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="admin-table-body">
                    @forelse ($requests as $row)
                        <tr class="admin-table-row">
                            <td class="admin-table-td">{{ $row->tracking_number }}</td>
                            <td class="admin-table-td">{{ $row->service?->office?->name ?? '-' }}</td>
                            <td class="admin-table-td">{{ $row->service?->name ?? '-' }}</td>
                            <td class="admin-table-td">{{ $row->status?->name ?? '-' }}</td>
                            <td class="admin-table-actions-cell">
                                <div class="admin-actions">
                                    <a href="{{ route('citizen.requests.show', $row->id) }}" class="btn-base btn-variant-white btn-xs">Show</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="admin-empty">No requests yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
