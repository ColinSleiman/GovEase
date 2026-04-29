@extends('layouts.admin')

@section('title', 'Edit Office | GovEase Admin')

@section('content')
<div class="space-y-6">

    <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-bold text-slate-900">Edit Office</h1>
        <p class="mt-2 text-sm text-slate-600">Update the selected office record.</p>
    </section>

    <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <form action="{{ route('admin.offices.update', $office->id) }}" method="POST" class="grid gap-5 md:grid-cols-2">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                <input type="text" name="name"
                    value="{{ old('name', $office->name) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Address</label>
                <input type="text" name="address"
                    value="{{ old('address', $office->address) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Working Hours</label>
                <input type="text" name="working_hours"
                    value="{{ old('working_hours', $office->working_hours) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Contact Info</label>
                <input type="text" name="contact_info"
                    value="{{ old('contact_info', $office->contact_info) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Municipality</label>

                <select name="municipality_id" required
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">

                    <option value="">Select Municipality</option>

                    @foreach($municipalities as $municipality)
                        <option value="{{ $municipality->id }}"
                            {{ old('municipality_id', $office->municipality_id) == $municipality->id ? 'selected' : '' }}>
                            {{ $municipality->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="md:col-span-2 flex flex-wrap gap-3">
                <x-admin.button variant="green" type="submit">
                    Save Changes
                </x-admin.button>

                <x-admin.button :href="route('admin.offices.index')" variant="white">
                    Cancel
                </x-admin.button>
            </div>

        </form>
    </section>

</div>
@endsection