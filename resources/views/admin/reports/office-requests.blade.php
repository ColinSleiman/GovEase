@extends('layouts.admin')

@section('title', 'Requests per Office | GovEase Admin')

@section('content')
    <div class="admin-page">
        <section class="admin-page-header">
            <div>
                <h1 class="admin-page-title">Requests per Office</h1>
                <p class="admin-page-subtitle">Track the number of requests handled by each office.</p>
            </div>
        </section>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="card-padded">
                <p class="text-xs uppercase tracking-wide text-slate-500">Total Requests</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $totalRequests }}</p>
            </div>
            <div class="card-padded">
                <p class="text-xs uppercase tracking-wide text-slate-500">Offices in Report</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $rows->total() }}</p>
            </div>
        </div>

        <section class="card-padded">
            <form method="GET" action="{{ route('admin.reports.office-requests') }}" class="grid gap-4 md:grid-cols-[minmax(0,1fr)_auto]">
                <div>
                    <label class="form-label">Municipality</label>
                    <select name="municipality_id" class="form-control-base">
                        <option value="">All municipalities</option>
                        @foreach ($municipalities as $municipality)
                            <option value="{{ $municipality->id }}" @selected((string) $filters['municipality_id'] === (string) $municipality->id)>{{ $municipality->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-3">
                    <x-admin.actions.button variant="blue" type="submit">Apply Filter</x-admin.actions.button>
                    <x-admin.actions.button :href="route('admin.reports.office-requests')" variant="white">Reset</x-admin.actions.button>
                </div>
            </form>
        </section>

        <section class="admin-table-wrap">
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead class="admin-table-head">
                        <tr>
                            <th class="admin-table-th">Office</th>
                            <th class="admin-table-th">Municipality</th>
                            <th class="admin-table-th">Request Count</th>
                        </tr>
                    </thead>
                    <tbody class="admin-table-body">
                        @forelse ($rows as $row)
                            <tr class="admin-table-row">
                                <td class="admin-table-td">{{ $row->name }}</td>
                                <td class="admin-table-td">{{ $row->municipality?->name ?? '-' }}</td>
                                <td class="admin-table-td">{{ $row->requests_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="admin-empty">No offices found for this report.</td>
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
