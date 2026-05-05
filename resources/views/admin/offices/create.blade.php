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

            <div class="md:col-span-2 flex flex-wrap gap-3">
                <x-admin.actions.button variant="green" type="submit">Create Office</x-admin.actions.button>
                <x-admin.actions.button :href="route('admin.offices.index')" variant="white">Cancel</x-admin.actions.button>
            </div>

        </form>

    </section>
</div>
@endsection
