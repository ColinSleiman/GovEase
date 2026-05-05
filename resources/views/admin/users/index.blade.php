@extends('layouts.admin')

@section('title', 'Users | GovEase Admin')

@section('content')
    <div class="admin-page">
        <section class="admin-page-header">
            <div>
                <h1 class="admin-page-title">Users</h1>
                <p class="admin-page-subtitle">Manage user records with simple CRUD actions.</p>
            </div>
            <x-admin.actions.button :href="route('admin.users.create')" variant="green">Create New User</x-admin.actions.button>
        </section>

        <div class="admin-table-wrap">
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead class="admin-table-head">
                        <tr>
                            <th class="admin-table-th">ID</th>
                            <th class="admin-table-th">First Name</th>
                            <th class="admin-table-th">Last Name</th>
                            <th class="admin-table-th">Email</th>
                            <th class="admin-table-th">Email Verified At</th>
                            <th class="admin-table-th">Two Factor Authentication</th>
                            <th class="admin-table-th">Office</th>
                            <th class="admin-table-th">Role</th>
                            <th class="admin-table-th">Status</th>
                            <th class="admin-table-th">Created At</th>
                            <th class="admin-table-th">Updated At</th>
                            <th class="admin-table-th-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admin-table-body">
                        @forelse ($data as $user)
                            <tr class="admin-table-row">
                                <td class="admin-table-td">{{ $user->id }}</td>
                                <td class="admin-table-td">{{ $user->firstName }}</td>
                                <td class="admin-table-td">{{ $user->lastName }}</td>
                                <td class="admin-table-td">{{ $user->email }}</td>
                                <td class="admin-table-td">{{ $user->email_verified_at }}</td>
                                <td class="admin-table-td">{{ $user->two_factor_authentication }}</td>
                                <td class="admin-table-td">{{ $user->office?->name ?? 'None' }}</td>
                                <td class="admin-table-td">{{ $user->role?->name ?? 'None' }}</td>
                                <td class="admin-table-td font-medium {{ $user->is_active ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </td>
                                <td class="admin-table-td">{{ $user->created_at }}</td>
                                <td class="admin-table-td">{{ $user->updated_at }}</td>
                                <td class="admin-table-actions-cell">
                                    <div class="admin-actions">
                                        <x-admin.actions.button :href="route('admin.users.show', $user->id)" variant="white" class="btn-xs">View</x-admin.actions.button>
                                        <x-admin.actions.button :href="route('admin.users.edit', $user->id)" variant="blue" class="btn-xs">Edit</x-admin.actions.button>
                                        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <x-admin.actions.button :variant="$user->is_active ? 'red' : 'green'" type="submit" class="btn-xs">
                                                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                            </x-admin.actions.button>
                                        </form>
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
                                <td colspan="12" class="admin-empty">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
