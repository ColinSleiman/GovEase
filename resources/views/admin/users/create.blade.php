@extends('layouts.admin')

@section('title', 'Create User | GovEase Admin')

@section('content')
    <div class="space-y-6">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-bold text-slate-900">Create User</h1>
            <p class="mt-2 text-sm text-slate-600">Add a new user record.</p>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form action="{{ route('admin.users.store') }}" method="POST" class="grid gap-5 md:grid-cols-2">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">First Name</label>
                    <input type="text" name="firstName" value="{{ old('firstName') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Last Name</label>
                    <input type="text" name="lastName" value="{{ old('lastName') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
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
                            <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>
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
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 flex flex-wrap gap-3">
                    <x-admin.button variant="green" type="submit">Create User</x-admin.button>
                    <x-admin.button :href="route('admin.users.index')" variant="white">Cancel</x-admin.button>
                </div>
            </form>
        </section>
    </div>
@endsection
