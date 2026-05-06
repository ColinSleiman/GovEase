@extends('layouts.citizen')

@section('title', ($title ?? 'My Requests') . ' | GovEase')

@section('content')
    <div class="citizen-page">
        <section class="card-padded">
            <div class="card-header">
                <div>
                    <h1 class="citizen-page-title">My Requests</h1>
                    <p class="citizen-page-subtitle">Track request status and open details for uploaded documents.</p>
                </div>
                @if (strtolower((string) auth()->user()?->role?->name) === 'citizen')
                    <a href="{{ route('citizen.requests.create') }}" class="btn-base btn-variant-blue">New Request</a>
                @endif
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead class="admin-table-head">
                    <tr>
                        <th class="admin-table-th">Tracking #</th>
                        <th class="admin-table-th">Office</th>
                        <th class="admin-table-th">Service</th>
                        <th class="admin-table-th">Status</th>
                        <th class="admin-table-th">Updated</th>
                        <th class="admin-table-th-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="admin-table-body">
                    @forelse ($data as $row)
                        <tr class="admin-table-row">
                            <td class="admin-table-td">{{ $row->tracking_number }}</td>
                            <td class="admin-table-td">{{ $row->service?->office?->name ?? '-' }}</td>
                            <td class="admin-table-td">{{ $row->service?->name ?? '-' }}</td>
                            <td class="admin-table-td">{{ $row->status?->name ?? '-' }}</td>
                            <td class="admin-table-td">{{ $row->updated_at?->diffForHumans() }}</td>
                            <td class="admin-table-actions-cell">
                                <div class="admin-actions">
                                    <a href="{{ route('citizen.requests.show', $row->id) }}" class="btn-base btn-variant-white btn-xs">View</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="admin-empty">No requests yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
