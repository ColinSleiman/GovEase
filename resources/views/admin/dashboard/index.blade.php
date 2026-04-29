@extends('layouts.admin')

@section('title', 'Admin Dashboard | GovEase')

@section('content')
    <div class="space-y-6">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-bold text-slate-900">Admin Dashboard</h1>
            <p class="mt-2 text-sm text-slate-600">
                Manage offices, municipalities, and users with simple CRUD pages and quick preview tables.
            </p>
        </section>

        <section class="space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Municipalities</h2>
                        <p class="text-sm text-slate-600">Latest 5 municipality records.</p>
                    </div>
                    <div class="flex gap-3">
                        <x-admin.button :href="route('admin.municipalities.create')" variant="green">Create Municipality</x-admin.button>
                        <x-admin.button :href="route('admin.municipalities.index')" variant="white">View All</x-admin.button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Region</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse ($municipalities as $municipality)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $municipality->id }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $municipality->name }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $municipality->region }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex justify-end gap-2">
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
                                    <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500">No municipalities found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Offices</h2>
                        <p class="text-sm text-slate-600">Latest 5 office records.</p>
                    </div>
                    <div class="flex gap-3">
                        <x-admin.button :href="route('admin.offices.create')" variant="green">Create Office</x-admin.button>
                        <x-admin.button :href="route('admin.offices.index')" variant="white">View All</x-admin.button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Municipality ID</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse ($offices as $office)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $office->id }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $office->name }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $office->municipality_id }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex justify-end gap-2">
                                            <x-admin.button :href="route('admin.offices.edit', $office->id)" variant="blue" class="px-3 py-1.5 text-xs">Edit</x-admin.button>
                                            <form action="{{ route('admin.offices.destroy', $office->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <x-admin.button variant="red" type="submit" class="px-3 py-1.5 text-xs">Delete</x-admin.button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500">No offices found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Users</h2>
                        <p class="text-sm text-slate-600">Latest 5 user records.</p>
                    </div>
                    <div class="flex gap-3">
                        <x-admin.button :href="route('admin.users.create')" variant="green">Create User</x-admin.button>
                        <x-admin.button :href="route('admin.users.index')" variant="white">View All</x-admin.button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Email</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse ($users as $user)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $user->id }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $user->full_name }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $user->email }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex justify-end gap-2">
                                            <x-admin.button :href="route('admin.users.edit', $user->id)" variant="blue" class="px-3 py-1.5 text-xs">Edit</x-admin.button>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <x-admin.button variant="red" type="submit" class="px-3 py-1.5 text-xs">Delete</x-admin.button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection
