@extends('layouts.admin')

@section('title', 'Edit Office | GovEase Admin')

@section('content')
<div class="admin-page">

    <section class="card-padded">
        <h1 class="admin-page-title">Edit Office</h1>
        <p class="admin-page-subtitle">Update the selected office record.</p>
    </section>

    <section class="card-padded">

        <form action="{{ route('admin.offices.update', $office->id) }}" method="POST" class="admin-form-grid">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 md:col-span-2">
                    <p class="font-semibold">Please fix the highlighted office details and try again.</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label class="form-label">Name</label>
                <input type="text" name="name"
                    value="{{ old('name', $office->name) }}"
                    class="form-control-base">
            </div>

            <div>
                <label class="form-label">Address</label>
                <input type="text" name="address"
                    value="{{ old('address', $office->address) }}"
                    class="form-control-base">
            </div>

            <div>
                <label class="form-label">Working Hours</label>
                <input type="text" name="working_hours"
                    value="{{ old('working_hours', $office->working_hours) }}"
                    class="form-control-base">
            </div>

            <div>
                <label class="form-label">Contact Info</label>
                <input type="text" name="contact_info"
                    value="{{ old('contact_info', $office->contact_info) }}"
                    class="form-control-base">
            </div>

            <div>
                <label class="form-label">Municipality</label>

                <select name="municipality_id" required
                    class="form-control-base">

                    <option value="">Select Municipality</option>

                    @foreach($municipalities as $municipality)
                        <option value="{{ $municipality->id }}"
                            {{ old('municipality_id', $office->municipality_id) == $municipality->id ? 'selected' : '' }}>
                            {{ $municipality->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 md:col-span-2">
                <h2 class="text-lg font-semibold text-slate-900">Optional Office Staff Login</h2>
                <p class="mt-1 text-sm text-slate-600">
                    Add a new office staff account for this office without leaving the page.
                </p>

                <div class="mt-4 grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="form-label">Staff First Name</label>
                        <input type="text" name="staff_first_name"
                            value="{{ old('staff_first_name') }}"
                            class="form-control-base">
                    </div>

                    <div>
                        <label class="form-label">Staff Last Name</label>
                        <input type="text" name="staff_last_name"
                            value="{{ old('staff_last_name') }}"
                            class="form-control-base">
                    </div>

                    <div>
                        <label class="form-label">Staff Email</label>
                        <input type="email" name="staff_email"
                            value="{{ old('staff_email') }}"
                            class="form-control-base">
                    </div>

                    <div>
                        <label class="form-label">Staff Password</label>
                        <input type="password" name="staff_password"
                            class="form-control-base">
                    </div>
                </div>
            </div>

            <div class="admin-form-actions">
                <x-admin.actions.button variant="green" type="submit">
                    Save Changes
                </x-admin.actions.button>

                <x-admin.actions.button :href="route('admin.offices.index')" variant="white">
                    Cancel
                </x-admin.actions.button>
            </div>

        </form>
    </section>

</div>
@endsection
