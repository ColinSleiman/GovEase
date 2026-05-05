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