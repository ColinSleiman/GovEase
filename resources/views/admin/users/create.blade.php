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
                    <label class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Email Verified At</label>
                    <input type="datetime-local" name="email_verified_at" value="{{ old('email_verified_at') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                </div>
                <div class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                    <input type="hidden" name="two_factor_authentication" value="0">
                    <input type="checkbox" name="two_factor_authentication" value="1" @checked(old('two_factor_authentication')) class="h-4 w-4 rounded border-slate-300 text-blue-600">
                    <label class="text-sm font-medium text-slate-700">Two Factor Authentication</label>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                    <input type="password" name="password" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Office ID</label>
                    <input type="number" name="office_id" value="{{ old('office_id') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Role ID</label>
                    <input type="number" name="role_id" value="{{ old('role_id') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                </div>

                <div class="md:col-span-2 flex flex-wrap gap-3">
                    <x-admin.button variant="green" type="submit">Create User</x-admin.button>
                    <x-admin.button :href="route('admin.users.index')" variant="white">Cancel</x-admin.button>
                </div>
            </form>
        </section>
    </div>
@endsection
