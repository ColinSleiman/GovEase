@extends('layouts.admin')

@section('title', 'User Details | GovEase Admin')

@section('content')
    <div class="space-y-6">
        <section class="flex flex-col gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">User Details</h1>
                <p class="mt-2 text-sm text-slate-600">Review the selected user record.</p>
            </div>
            <div class="flex gap-3">
                <x-admin.actions.button :href="route('admin.users.edit', $row->id)" variant="blue">Edit User</x-admin.actions.button>
                <form action="{{ route('admin.users.toggle-status', $row->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <x-admin.actions.button :variant="$row->is_active ? 'red' : 'green'" type="submit">
                        {{ $row->is_active ? 'Deactivate User' : 'Activate User' }}
                    </x-admin.actions.button>
                </form>
                <x-admin.actions.button :href="route('admin.users.index')" variant="white">Back to Users</x-admin.actions.button>
            </div>
        </section>

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <dl class="divide-y divide-slate-200">
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">ID</dt><dd class="text-sm text-slate-900">{{ $row->id }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">First Name</dt><dd class="text-sm text-slate-900">{{ $row->firstName }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Last Name</dt><dd class="text-sm text-slate-900">{{ $row->lastName }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Email</dt><dd class="text-sm text-slate-900">{{ $row->email }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Email Verified At</dt><dd class="text-sm text-slate-900">{{ $row->email_verified_at }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Two Factor Authentication</dt><dd class="text-sm text-slate-900">{{ $row->two_factor_authentication }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Office ID - Name</dt><dd class="text-sm text-slate-900">{{ $row->office_id }} - {{ $row->office?->name}}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Role</dt><dd class="text-sm text-slate-900">{{ $row->role?->name}}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Account Status</dt><dd class="text-sm font-medium {{ $row->is_active ? 'text-emerald-600' : 'text-red-600' }}">{{ $row->is_active ? 'Active' : 'Inactive' }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Created At</dt><dd class="text-sm text-slate-900">{{ $row->created_at }}</dd></div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]"><dt class="text-sm font-semibold text-slate-600">Updated At</dt><dd class="text-sm text-slate-900">{{ $row->updated_at }}</dd></div>
            </dl>
        </section>
    </div>
@endsection
