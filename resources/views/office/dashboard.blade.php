@extends('layouts.office')

@section('title', 'Office Dashboard | GovEase')

@section('content')
    <div class="admin-page">
        <section class="card-padded">
            <h1 class="admin-page-title">Office Dashboard</h1>
            <p class="admin-page-subtitle">Monitor incoming requests and manage office services efficiently.</p>
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
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Missing Documents</p>
                <p class="mt-2 text-2xl font-bold text-orange-600">{{ $missingDocumentsCount }}</p>
            </article>
            <article class="card-padded">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Approved</p>
                <p class="mt-2 text-2xl font-bold text-emerald-600">{{ $approvedCount }}</p>
            </article>
            <article class="card-padded">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Rejected</p>
                <p class="mt-2 text-2xl font-bold text-red-600">{{ $rejectedCount }}</p>
            </article>
            <article class="card-padded">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Completed</p>
                <p class="mt-2 text-2xl font-bold text-emerald-700">{{ $completedCount }}</p>
            </article>
            <article class="card-padded">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Service Categories</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ $categoryCount }}</p>
            </article>
            <article class="card-padded">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Services</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ $serviceCount }}</p>
            </article>
        </section>

        <section class="card-padded">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Service Management</h2>
                    <p class="card-subtitle">Create, update, and delete service categories and services for your office.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <x-office.actions.button :href="route('office.service-categories.index')" variant="white">Manage Categories</x-office.actions.button>
                    <x-office.actions.button :href="route('office.services.index')" variant="blue">Manage Services</x-office.actions.button>
                </div>
            </div>
        </section>

        <section class="card-padded">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Recent Incoming Requests</h2>
                    <p class="card-subtitle">Latest requests submitted to your office.</p>
                </div>
                <x-office.actions.button :href="route('office.requests.index')" variant="white">View All Requests</x-office.actions.button>
            </div>

            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead class="admin-table-head">
                        <tr>
                            <th class="admin-table-th">Request ID</th>
                            <th class="admin-table-th">Citizen</th>
                            <th class="admin-table-th">Category</th>
                            <th class="admin-table-th">Service</th>
                            <th class="admin-table-th">Status</th>
                            <th class="admin-table-th">Submitted</th>
                            <th class="admin-table-th-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admin-table-body">
                        @forelse ($recentRequests as $row)
                            <tr class="admin-table-row">
                                <td class="admin-table-td">#{{ $row->id }}</td>
                                <td class="admin-table-td">{{ $row->user?->full_name ?? '-' }}</td>
                                <td class="admin-table-td">{{ $row->service?->serviceCategory?->name ?? '-' }}</td>
                                <td class="admin-table-td">{{ $row->service?->name ?? '-' }}</td>
                                <td class="admin-table-td">{{ $row->status?->name ?? '-' }}</td>
                                <td class="admin-table-td">{{ $row->created_at?->format('M d, Y h:i A') }}</td>
                                <td class="admin-table-actions-cell">
                                    <div class="admin-actions">
                                        <x-office.actions.button :href="route('office.requests.show', $row->id)" variant="white" class="btn-xs">View</x-office.actions.button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="admin-empty">No incoming requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
