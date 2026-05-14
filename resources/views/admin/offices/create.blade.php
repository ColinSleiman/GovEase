@extends('layouts.admin')

@section('title', 'Create Office | GovEase Admin')

@section('content')
<div class="space-y-6">

    <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-bold text-slate-900">Create Office</h1>
        <p class="mt-2 text-sm text-slate-600">Add a new office record.</p>
    </section>

    <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <form action="{{ route('admin.offices.store') }}" method="POST" class="grid gap-5 md:grid-cols-2">
            @csrf

            @if ($errors->any())
                <div class="md:col-span-2 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold">Please fix the highlighted office details and try again.</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Address</label>
                <input type="text" name="address" value="{{ old('address') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Working Hours</label>
                <input type="text" name="working_hours" value="{{ old('working_hours') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Contact Info</label>
                <input type="text" name="contact_info" value="{{ old('contact_info') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Municipality</label>

                <select name="municipality_id" required class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">

                    <option value="">Select Municipality</option>

                    @foreach($data as $municipality)
                        <option value="{{ $municipality->id }}"
                            {{ old('municipality_id') == $municipality->id ? 'selected' : '' }}>
                            {{ $municipality->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="md:col-span-2 rounded-xl border border-slate-200 bg-slate-50 p-5">
                <h2 class="text-lg font-semibold text-slate-900">Optional Office Staff Login</h2>
                <p class="mt-1 text-sm text-slate-600">
                    Create the first office staff account now so this office can immediately add categories and services.
                </p>

                <div class="mt-4 grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Staff First Name</label>
                        <input type="text" name="staff_first_name" value="{{ old('staff_first_name') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Staff Last Name</label>
                        <input type="text" name="staff_last_name" value="{{ old('staff_last_name') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Staff Email</label>
                        <input type="email" name="staff_email" value="{{ old('staff_email') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Staff Password</label>
                        <input type="password" name="staff_password" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 flex flex-wrap gap-3">
                <x-admin.actions.button variant="green" type="submit">Create Office</x-admin.actions.button>
                <x-admin.actions.button :href="route('admin.offices.index')" variant="white">Cancel</x-admin.actions.button>
            </div>

        </form>

    </section>
</div>
@endsection
