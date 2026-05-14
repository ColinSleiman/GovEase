@extends('layouts.office')

@section('title', 'Appointment Details | GovEase Office')

@section('content')
    <div class="admin-page">
        <section class="admin-page-header">
            <div>
                <h1 class="admin-page-title">Appointment Details</h1>
                <p class="admin-page-subtitle">Review this citizen appointment for a physical office visit.</p>
            </div>
            <x-office.actions.button :href="route('office.appointments.index')" variant="white">Back to Appointments</x-office.actions.button>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <article class="card-padded">
                <h2 class="card-title">Citizen</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between gap-4"><dt class="font-semibold text-slate-600">Name</dt><dd class="text-slate-900">{{ $row->user?->full_name ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="font-semibold text-slate-600">Email</dt><dd class="text-slate-900">{{ $row->user?->email ?? '-' }}</dd></div>
                </dl>
            </article>

            <article class="card-padded">
                <h2 class="card-title">Appointment</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between gap-4"><dt class="font-semibold text-slate-600">Municipality</dt><dd class="text-slate-900">{{ $row->office?->municipality?->name ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="font-semibold text-slate-600">Office</dt><dd class="text-slate-900">{{ $row->office?->name ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="font-semibold text-slate-600">Service</dt><dd class="text-slate-900">{{ $row->service?->name ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="font-semibold text-slate-600">Date</dt><dd class="text-slate-900">{{ \Carbon\Carbon::parse($row->appointment_date)->format('M d, Y') }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="font-semibold text-slate-600">Time</dt><dd class="text-slate-900">{{ \Carbon\Carbon::parse($row->appointment_time)->format('h:i A') }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="font-semibold text-slate-600">Status</dt><dd class="text-slate-900">{{ $row->status?->name ?? '-' }}</dd></div>
                </dl>
            </article>
        </section>
    </div>
@endsection
