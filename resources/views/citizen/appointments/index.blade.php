@extends('layouts.citizen')

@section('title', 'My Appointments | GovEase')

@section('content')
    <div class="citizen-page">
        <section class="card-padded">
            <div class="card-header">
                <div>
                    <h1 class="citizen-page-title">My Physical Visit Appointments</h1>
                    <p class="citizen-page-subtitle">Track the appointments you booked for in-person office visits.</p>
                </div>
                <a href="{{ route('citizen.appointments.create') }}" class="btn-base btn-variant-blue">Book Appointment</a>
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
                        <th class="admin-table-th">Office</th>
                        <th class="admin-table-th">Service</th>
                        <th class="admin-table-th">Date</th>
                        <th class="admin-table-th">Time</th>
                        <th class="admin-table-th">Status</th>
                    </tr>
                    </thead>
                    <tbody class="admin-table-body">
                    @forelse ($appointments as $row)
                        <tr class="admin-table-row">
                            <td class="admin-table-td">{{ $row->office?->name ?? '-' }}</td>
                            <td class="admin-table-td">{{ $row->service?->name ?? '-' }}</td>
                            <td class="admin-table-td">{{ \Carbon\Carbon::parse($row->appointment_date)->format('M d, Y') }}</td>
                            <td class="admin-table-td">{{ \Carbon\Carbon::parse($row->appointment_time)->format('h:i A') }}</td>
                            <td class="admin-table-td">{{ $row->status?->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="admin-empty">No appointments booked yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $appointments->links() }}
            </div>
        </section>
    </div>
@endsection
