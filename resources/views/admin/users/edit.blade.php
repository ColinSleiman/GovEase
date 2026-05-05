@extends('layouts.admin')

@section('title', 'Edit User | GovEase Admin')

@section('content')
    <div class="space-y-6">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-bold text-slate-900">Edit User</h1>
            <p class="mt-2 text-sm text-slate-600">Update the selected user record.</p>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form action="{{ route('admin.users.update', $row->id) }}" method="POST" class="grid gap-5 md:grid-cols-2">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">First Name</label>
                    <input type="text" name="firstName" value="{{ old('firstName', $row->firstName) }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Last Name</label>
                    <input type="text" name="lastName" value="{{ old('lastName', $row->lastName) }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $row->email) }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                    <input type="password" name="password" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Office</label>
                    <select name="office_id" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                        <option value="">Select Office</option>
                        @foreach($offices as $office)
                            <option value="{{ $office->id }}" {{ old('office_id', $row->office_id) == $office->id ? 'selected' : '' }}>
                                {{ $office->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Role</label>
                    <select name="role_id" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $row->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 flex flex-wrap gap-3">
                    <x-admin.actions.button variant="green" type="submit">Save Changes</x-admin.actions.button>
                    <x-admin.actions.button :href="route('admin.users.index')" variant="white">Cancel</x-admin.actions.button>
                </div>
            </form>
        </section>
    </div>
@endsection
