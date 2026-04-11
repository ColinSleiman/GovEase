@extends('layouts.admin')

@section('title', 'Municipalities | GovEase Admin')

@section('content')
    <div class="space-y-6">
        <section class="flex flex-col gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Municipalities</h1>
                <p class="mt-2 text-sm text-slate-600">Manage municipality records with simple CRUD actions.</p>
            </div>
            <x-admin.button :href="route('admin.municipalities.create')" variant="green">Create New Municipality</x-admin.button>
        </section>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Region</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Latitude</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Longitude</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Address</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Google Maps Location</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Working Hours</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Contact Info</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Created At</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Updated At</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($data as $municipality)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-sm text-slate-700">{{ $municipality->id }}</td>
                                <td class="px-4 py-3 text-sm text-slate-700">{{ $municipality->name }}</td>
                                <td class="px-4 py-3 text-sm text-slate-700">{{ $municipality->region }}</td>
                                <td class="max-w-[140px] truncate px-4 py-3 text-sm text-slate-700" title="{{ $municipality->latitude }}">
                                    {{ $municipality->latitude ?? 'N/A' }}
                                </td>
                                <td class="max-w-[140px] truncate px-4 py-3 text-sm text-slate-700" title="{{ $municipality->longitude }}">
                                    {{ $municipality->longitude ?? 'N/A' }}
                                </td>
                                <td class="max-w-xs truncate px-4 py-3 text-sm text-slate-700" title="{{ $municipality->address }}">
                                    {{ $municipality->address ?? 'N/A' }}
                                </td>
                                <td class="max-w-xs truncate px-4 py-3 text-sm text-slate-700" title="{{ $municipality->google_maps_location }}">
                                    {{ $municipality->google_maps_location ?? 'N/A' }}
                                </td>
                                <td class="max-w-[180px] truncate px-4 py-3 text-sm text-slate-700" title="{{ $municipality->working_hours }}">
                                    {{ $municipality->working_hours ?? 'N/A' }}
                                </td>
                                <td class="max-w-[180px] truncate px-4 py-3 text-sm text-slate-700" title="{{ $municipality->contact_info }}">
                                    {{ $municipality->contact_info ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-700">{{ $municipality->created_at }}</td>
                                <td class="px-4 py-3 text-sm text-slate-700">{{ $municipality->updated_at }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <x-admin.button :href="route('admin.municipalities.show', $municipality->id)" variant="white" class="px-3 py-1.5 text-xs">View</x-admin.button>
                                        <x-admin.button :href="route('admin.municipalities.edit', $municipality->id)" variant="blue" class="px-3 py-1.5 text-xs">Edit</x-admin.button>
                                        <form action="{{ route('admin.municipalities.destroy', $municipality->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <x-admin.button variant="red" type="submit" class="px-3 py-1.5 text-xs">Delete</x-admin.button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-4 py-6 text-center text-sm text-slate-500">No municipalities found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
