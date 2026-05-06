@extends('layouts.office')

@section('title', 'Edit Service | GovEase Office')

@section('content')
    <div class="admin-page">
        <section class="card-padded">
            <h1 class="admin-page-title">Edit Service</h1>
            <p class="admin-page-subtitle">Update this service offering for your office.</p>
        </section>

        <section class="card-padded">
            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('office.services.update', $row->id) }}" method="POST" class="admin-form-grid">
                @csrf
                @method('PUT')

                <div>
                    <label class="form-label" for="name">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $row->name) }}" class="form-control-base" required>
                </div>

                <div>
                    <label class="form-label" for="service_category_id">Service Category</label>
                    <select id="service_category_id" name="service_category_id" class="form-control-base" required>
                        <option value="">Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('service_category_id', $row->service_category_id) === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label" for="description">Description</label>
                    <textarea id="description" name="description" rows="4" class="form-control-base" required>{{ old('description', $row->description) }}</textarea>
                </div>

                <div>
                    <label class="form-label" for="price">Price</label>
                    <input type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price', $row->price) }}" class="form-control-base" required>
                </div>

                <div>
                    <label class="form-label" for="duration">Duration (Minutes)</label>
                    <input type="number" min="1" id="duration" name="duration" value="{{ old('duration', $row->duration) }}" class="form-control-base" required>
                </div>

                <div class="admin-form-actions">
                    <x-office.actions.button variant="green" type="submit">Save Changes</x-office.actions.button>
                    <x-office.actions.button :href="route('office.services.index')" variant="white">Cancel</x-office.actions.button>
                </div>
            </form>
        </section>
    </div>
@endsection
