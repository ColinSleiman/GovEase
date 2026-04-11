@extends('layouts.admin')

@section('title', 'Municipality Details | GovEase Admin')

@section('content')
    @php
        $mapsValue = $municipality->google_maps_location;
        $mapsUrl = null;

        if ($mapsValue) {
            $mapsUrl = str_starts_with($mapsValue, 'http://') || str_starts_with($mapsValue, 'https://')
                ? $mapsValue
                : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($municipality->address ?: $mapsValue);
        }
    @endphp

    <div class="space-y-6">
        <section class="flex flex-col gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Municipality Details</h1>
                <p class="mt-2 text-sm text-slate-600">Review the selected municipality record.</p>
            </div>
            <div class="flex gap-3">
                <x-admin.button :href="route('admin.municipalities.edit', $municipality->id)" variant="blue">Edit Municipality</x-admin.button>
                <x-admin.button :href="route('admin.municipalities.index')" variant="white">Back to Municipalities</x-admin.button>
            </div>
        </section>

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <dl class="divide-y divide-slate-200">
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">ID</dt><dd class="text-sm text-slate-900">{{ $municipality->id }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Name</dt><dd class="text-sm text-slate-900">{{ $municipality->name }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Region</dt><dd class="text-sm text-slate-900">{{ $municipality->region }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Latitude</dt><dd class="text-sm text-slate-900">{{ $municipality->latitude ?? 'N/A' }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Longitude</dt><dd class="text-sm text-slate-900">{{ $municipality->longitude ?? 'N/A' }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Address</dt><dd class="text-sm text-slate-900">{{ $municipality->address ?? 'N/A' }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]">
                    <dt class="text-sm font-semibold text-slate-600">Google Maps Location</dt>
                    <dd class="text-sm text-slate-900">
                        @if ($mapsUrl)
                            <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-700 hover:underline">
                                {{ $municipality->google_maps_location }}
                            </a>
                        @else
                            N/A
                        @endif
                    </dd>
                </div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Working Hours</dt><dd class="text-sm text-slate-900">{{ $municipality->working_hours ?? 'N/A' }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Contact Info</dt><dd class="text-sm text-slate-900">{{ $municipality->contact_info ?? 'N/A' }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Created At</dt><dd class="text-sm text-slate-900">{{ $municipality->created_at }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Updated At</dt><dd class="text-sm text-slate-900">{{ $municipality->updated_at }}</dd></div>
            </dl>
        </section>
    </div>
@endsection
