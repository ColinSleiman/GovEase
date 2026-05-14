@extends('layouts.office')

@section('title', 'Appointments | GovEase Office')

@section('content')
    <div class="admin-page">
        <section class="admin-page-header">
            <div>
                <h1 class="admin-page-title">Appointment Management</h1>
                <p class="admin-page-subtitle">View, edit, and delete citizen appointments for physical visits.</p>
            </div>
        </section>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <section class="card-padded">
            <form method="GET" action="{{ route('office.appointments.index') }}" class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="form-label">Search</label>
                    <input type="text" name="search" value="{{ $filters['search'] }}" class="form-control-base" placeholder="Citizen or service">
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
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] }}" class="form-control-base">
                </div>
                <div>
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] }}" class="form-control-base">
                </div>
                <div class="md:col-span-3 flex flex-wrap gap-3">
                    <x-office.actions.button variant="blue" type="submit">Apply Filters</x-office.actions.button>
                    <x-office.actions.button :href="route('office.appointments.index')" variant="white">Reset</x-office.actions.button>
                </div>
            </form>
        </section>

        <section class="admin-table-wrap">
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead class="admin-table-head">
                        <tr>
                            <th class="admin-table-th">Citizen</th>
                            <th class="admin-table-th">Service</th>
                            <th class="admin-table-th">Date</th>
                            <th class="admin-table-th">Time</th>
                            <th class="admin-table-th">Status</th>
                            <th class="admin-table-th-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admin-table-body">
                        @forelse ($rows as $row)
                            <tr class="admin-table-row">
                                <td class="admin-table-td">{{ $row->user?->full_name ?? '-' }}</td>
                                <td class="admin-table-td">{{ $row->service?->name ?? '-' }}</td>
                                <td class="admin-table-td">{{ \Carbon\Carbon::parse($row->appointment_date)->format('M d, Y') }}</td>
                                <td class="admin-table-td">{{ \Carbon\Carbon::parse($row->appointment_time)->format('h:i A') }}</td>
                                <td class="admin-table-td">{{ $row->status?->name ?? '-' }}</td>
                                <td class="admin-table-actions-cell">
                                    <div class="admin-actions">
                                        <x-office.actions.button :href="route('office.appointments.show', $row->id)" variant="white" class="btn-xs">View</x-office.actions.button>
                                        <x-office.actions.button :href="route('office.appointments.edit', $row->id)" variant="blue" class="btn-xs">Edit</x-office.actions.button>
                                        <form action="{{ route('office.appointments.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Delete this appointment?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-office.actions.button variant="red" type="submit" class="btn-xs">Delete</x-office.actions.button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="admin-empty">No appointments found for your office.</td>
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
