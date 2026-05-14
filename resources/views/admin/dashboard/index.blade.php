@extends('layouts.admin')

@section('title', 'Admin Dashboard | GovEase')

@section('content')
    <div class="admin-page">
        <section class="card-padded">
            <h1 class="admin-page-title">Admin Dashboard</h1>
            <p class="admin-page-subtitle">
                Oversee requests, services, municipalities, offices, users, and revenue from one place.
            </p>
        </section>

        <section class="space-y-6">
            <div class="grid gap-4 md:grid-cols-4">
                <div class="card-padded">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Total Services</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $serviceCount }}</p>
                </div>
                <div class="card-padded">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Total Requests</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $requestCount }}</p>
                </div>
                <div class="card-padded">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Pending Requests</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $pendingRequestCount }}</p>
                </div>
                <div class="card-padded">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Completed Revenue</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">${{ number_format($revenueTotal, 2) }}</p>
                </div>
            </div>

            <div class="card-padded">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">Incoming Requests</h2>
                        <p class="card-subtitle">Latest citizen requests across all offices.</p>
                    </div>
                    <div class="flex gap-3">
                        <x-admin.actions.button :href="route('admin.requests.index')" variant="white">Manage All Requests</x-admin.actions.button>
                    </div>
                </div>
                <div class="admin-table-scroll">
                    <table class="admin-table">
                        <thead class="admin-table-head">
                            <tr>
                                <th class="admin-table-th">Tracking #</th>
                                <th class="admin-table-th">Citizen</th>
                                <th class="admin-table-th">Office</th>
                                <th class="admin-table-th">Status</th>
                                <th class="admin-table-th-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="admin-table-body">
                            @forelse ($requests as $request)
                                <tr class="admin-table-row">
                                    <td class="admin-table-td">{{ $request->tracking_number }}</td>
                                    <td class="admin-table-td">{{ $request->user?->full_name ?? '-' }}</td>
                                    <td class="admin-table-td">{{ $request->service?->office?->name ?? '-' }}</td>
                                    <td class="admin-table-td">{{ $request->status?->name ?? '-' }}</td>
                                    <td class="admin-table-actions-cell">
                                        <div class="admin-actions">
                                            <x-admin.actions.button :href="route('admin.requests.show', $request->id)" variant="blue" class="btn-xs">Open</x-admin.actions.button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="admin-empty">No requests found yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-padded">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">Municipalities</h2>
                        <p class="card-subtitle">Latest 5 municipality records.</p>
                    </div>
                    <div class="flex gap-3">
                        <x-admin.actions.button :href="route('admin.municipalities.create')" variant="green">Create Municipality</x-admin.actions.button>
                        <x-admin.actions.button :href="route('admin.municipalities.index')" variant="white">View All</x-admin.actions.button>
                    </div>
                </div>
                <div class="admin-table-scroll">
                    <table class="admin-table">
                        <thead class="admin-table-head">
                            <tr>
                                <th class="admin-table-th">ID</th>
                                <th class="admin-table-th">Name</th>
                                <th class="admin-table-th">Region</th>
                                <th class="admin-table-th-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="admin-table-body">
                            @forelse ($municipalities as $municipality)
                                <tr class="admin-table-row">
                                    <td class="admin-table-td">{{ $municipality->id }}</td>
                                    <td class="admin-table-td">{{ $municipality->name }}</td>
                                    <td class="admin-table-td">{{ $municipality->region }}</td>
                                    <td class="admin-table-actions-cell">
                                        <div class="admin-actions">
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
                                    <td colspan="4" class="admin-empty">No municipalities found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-padded">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">Offices</h2>
                        <p class="card-subtitle">Latest 5 office records.</p>
                    </div>
                    <div class="flex gap-3">
                        <x-admin.actions.button :href="route('admin.offices.create')" variant="green">Create Office</x-admin.actions.button>
                        <x-admin.actions.button :href="route('admin.offices.index')" variant="white">View All</x-admin.actions.button>
                    </div>
                </div>
                <div class="admin-table-scroll">
                    <table class="admin-table">
                        <thead class="admin-table-head">
                            <tr>
                                <th class="admin-table-th">ID</th>
                                <th class="admin-table-th">Name</th>
                                <th class="admin-table-th">Municipality ID</th>
                                <th class="admin-table-th">Services</th>
                                <th class="admin-table-th-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="admin-table-body">
                            @forelse ($offices as $office)
                                <tr class="admin-table-row">
                                    <td class="admin-table-td">{{ $office->id }}</td>
                                    <td class="admin-table-td">{{ $office->name }}</td>
                                    <td class="admin-table-td">{{ $office->municipality_id }}</td>
                                    <td class="admin-table-td">{{ $office->services_count }}</td>
                                    <td class="admin-table-actions-cell">
                                        <div class="admin-actions">
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
                                    <td colspan="5" class="admin-empty">No offices found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-padded">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">Users</h2>
                        <p class="card-subtitle">Latest 5 user records.</p>
                    </div>
                    <div class="flex gap-3">
                        <x-admin.actions.button :href="route('admin.users.create')" variant="green">Create User</x-admin.actions.button>
                        <x-admin.actions.button :href="route('admin.users.index')" variant="white">View All</x-admin.actions.button>
                    </div>
                </div>
                <div class="admin-table-scroll">
                    <table class="admin-table">
                        <thead class="admin-table-head">
                            <tr>
                                <th class="admin-table-th">ID</th>
                                <th class="admin-table-th">Name</th>
                                <th class="admin-table-th">Email</th>
                                <th class="admin-table-th-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="admin-table-body">
                            @forelse ($users as $user)
                                <tr class="admin-table-row">
                                    <td class="admin-table-td">{{ $user->id }}</td>
                                    <td class="admin-table-td">{{ $user->full_name }}</td>
                                    <td class="admin-table-td">{{ $user->email }}</td>
                                    <td class="admin-table-actions-cell">
                                        <div class="admin-actions">
                                            <x-admin.actions.button :href="route('admin.users.edit', $user->id)" variant="blue" class="btn-xs">Edit</x-admin.actions.button>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <x-admin.actions.button variant="red" type="submit" class="btn-xs">Delete</x-admin.actions.button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="admin-empty">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection
