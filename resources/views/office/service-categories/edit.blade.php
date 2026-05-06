@extends('layouts.office')

@section('title', 'Edit Service Category | GovEase Office')

@section('content')
    <div class="admin-page">
        <section class="card-padded">
            <h1 class="admin-page-title">Edit Service Category</h1>
            <p class="admin-page-subtitle">Update the selected service category.</p>
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

            <form action="{{ route('office.service-categories.update', $row->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="max-w-xl">
                    <label class="form-label" for="name">Category Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $row->name) }}"
                        class="form-control-base"
                        required
                    >
                </div>

                <div class="flex flex-wrap gap-3">
                    <x-office.actions.button variant="green" type="submit">Save Changes</x-office.actions.button>
                    <x-office.actions.button :href="route('office.service-categories.index')" variant="white">Cancel</x-office.actions.button>
                </div>
            </form>
        </section>
    </div>
@endsection
