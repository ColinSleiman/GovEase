@extends('layouts.admin')

@section('title', 'Municipalities | GovEase Admin')

@section('content')
    <div class="admin-page">
        <section class="admin-page-header">
            <div>
                <h1 class="admin-page-title">Municipalities</h1>
                <p class="admin-page-subtitle">Manage municipality records with simple CRUD actions.</p>
            </div>
            <x-admin.actions.button :href="route('admin.municipalities.create')" variant="green">Create New Municipality</x-admin.actions.button>
        </section>

        <div class="admin-table-wrap">
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead class="admin-table-head">
                        <tr>
                            <th class="admin-table-th">ID</th>
                            <th class="admin-table-th">Name</th>
                            <th class="admin-table-th">Region</th>
                            <th class="admin-table-th">Latitude</th>
                            <th class="admin-table-th">Longitude</th>
                            <th class="admin-table-th">Address</th>
                            <th class="admin-table-th">Google Maps Location</th>
                            <th class="admin-table-th">Working Hours</th>
                            <th class="admin-table-th">Contact Info</th>
                            <th class="admin-table-th">Created At</th>
                            <th class="admin-table-th">Updated At</th>
                            <th class="admin-table-th-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admin-table-body">
                        @forelse ($data as $municipality)
                            <tr class="admin-table-row">
                                <td class="admin-table-td">{{ $municipality->id }}</td>
                                <td class="admin-table-td">{{ $municipality->name }}</td>
                                <td class="admin-table-td">{{ $municipality->region }}</td>
                                <td class="admin-table-td max-w-[140px] truncate" title="{{ $municipality->latitude }}">
                                    {{ $municipality->latitude ?? 'N/A' }}
                                </td>
                                <td class="admin-table-td max-w-[140px] truncate" title="{{ $municipality->longitude }}">
                                    {{ $municipality->longitude ?? 'N/A' }}
                                </td>
                                <td class="admin-table-td max-w-xs truncate" title="{{ $municipality->address }}">
                                    {{ $municipality->address ?? 'N/A' }}
                                </td>
                                <td class="admin-table-td max-w-xs truncate" title="{{ $municipality->google_maps_location }}">
                                    {{ $municipality->google_maps_location ?? 'N/A' }}
                                </td>
                                <td class="admin-table-td max-w-[180px] truncate" title="{{ $municipality->working_hours }}">
                                    {{ $municipality->working_hours ?? 'N/A' }}
                                </td>
                                <td class="admin-table-td max-w-[180px] truncate" title="{{ $municipality->contact_info }}">
                                    {{ $municipality->contact_info ?? 'N/A' }}
                                </td>
                                <td class="admin-table-td">{{ $municipality->created_at }}</td>
                                <td class="admin-table-td">{{ $municipality->updated_at }}</td>
                                <td class="admin-table-actions-cell">
                                    <div class="admin-actions">
                                        <x-admin.actions.button :href="route('admin.municipalities.show', $municipality->id)" variant="white" class="btn-xs">View</x-admin.actions.button>
                                        <x-admin.actions.button :href="route('admin.municipalities.edit', $municipality->id)" variant="blue" class="btn-xs">Edit</x-admin.actions.button>
                                        <form action="{{ route('admin.municipalities.destroy', $municipality->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <x-admin.actions.button variant="red" type="submit" class="btn-xs">Delete</x-admin.actions.button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="admin-empty">No municipalities found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
