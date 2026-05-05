@extends('layouts.admin')

@section('title', 'Office Details | GovEase Admin')

@section('content')
    <div class="space-y-6">
        <section class="flex flex-col gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Office Details</h1>
                <p class="mt-2 text-sm text-slate-600">Review the selected office record.</p>
            </div>
            <div class="flex gap-3">
                <x-admin.actions.button :href="route('admin.offices.edit', $row->id)" variant="blue">Edit Office</x-admin.actions.button>
                <x-admin.actions.button :href="route('admin.offices.index')" variant="white">Back to Offices</x-admin.actions.button>
            </div>
        </section>

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <dl class="divide-y divide-slate-200">
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">ID</dt><dd class="text-sm text-slate-900">{{ $row->id }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Name</dt><dd class="text-sm text-slate-900">{{ $row->name }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Address</dt><dd class="text-sm text-slate-900">{{ $row->address }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Google Maps Location</dt><dd class="text-sm text-slate-900">{{ $row->google_maps_location }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Latitude</dt><dd class="text-sm text-slate-900">{{ $row->latitude }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Longitude</dt><dd class="text-sm text-slate-900">{{ $row->longitude }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Working Hours</dt><dd class="text-sm text-slate-900">{{ $row->working_hours }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Contact Info</dt><dd class="text-sm text-slate-900">{{ $row->contact_info }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Municipality ID</dt><dd class="text-sm text-slate-900">{{ $row->municipality_id }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Created At</dt><dd class="text-sm text-slate-900">{{ $row->created_at }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Updated At</dt><dd class="text-sm text-slate-900">{{ $row->updated_at }}</dd></div>
            </dl>
        </section>
    </div>
@endsection
