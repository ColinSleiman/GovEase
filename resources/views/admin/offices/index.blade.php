@extends('layouts.admin')

@section('title', 'Offices | GovEase Admin')

@section('content')
    <div class="admin-page">
        <section class="admin-page-header">
            <div>
                <h1 class="admin-page-title">Offices</h1>
                <p class="admin-page-subtitle">Manage office records with simple CRUD actions.</p>
            </div>
            <x-admin.actions.button :href="route('admin.offices.create')" variant="green">Create New Office</x-admin.actions.button>
        </section>

        <div class="admin-table-wrap">
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead class="admin-table-head">
                        <tr>
                            <th class="admin-table-th">ID</th>
                            <th class="admin-table-th">Name</th>
                            <th class="admin-table-th">Address</th>
                            <th class="admin-table-th">Google Maps Location</th>
                            <th class="admin-table-th">Latitude</th>
                            <th class="admin-table-th">Longitude</th>
                            <th class="admin-table-th">Working Hours</th>
                            <th class="admin-table-th">Contact Info</th>
                            <th class="admin-table-th">Municipality ID</th>
                            <th class="admin-table-th-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admin-table-body">
                        @forelse ($data as $office)
                            <tr class="admin-table-row">
                                <td class="admin-table-td">{{ $office->id }}</td>
                                <td class="admin-table-td">{{ $office->name }}</td>
                                <td class="admin-table-td">{{ $office->address }}</td>
                                <td class="admin-table-td">{{ $office->google_maps_location }}</td>
                                <td class="admin-table-td">{{ $office->latitude }}</td>
                                <td class="admin-table-td">{{ $office->longitude }}</td>
                                <td class="admin-table-td">{{ $office->working_hours }}</td>
                                <td class="admin-table-td">{{ $office->contact_info }}</td>
                                <td class="admin-table-td">{{ $office->municipality_id }} - {{ $office->municipality->name }}</td>
                                <td class="admin-table-actions-cell">
                                    <div class="admin-actions">
                                        <x-admin.actions.button :href="route('admin.offices.show', $office->id)" variant="white" class="btn-xs">View</x-admin.actions.button>
                                        <x-admin.actions.button :href="route('admin.offices.edit', $office->id)" variant="blue" class="btn-xs">Edit</x-admin.actions.button>
                                        <form action="{{ route('admin.offices.destroy', $office->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <x-admin.actions.button variant="red" type="submit" class="btn-xs">Delete</x-admin.actions.button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="admin-empty">No offices found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
