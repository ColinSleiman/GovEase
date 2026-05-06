@extends('layouts.office')

@section('title', 'Create Service Category | GovEase Office')

@section('content')
    <div class="admin-page">
        <section class="card-padded">
            <h1 class="admin-page-title">Create Service Category</h1>
            <p class="admin-page-subtitle">Add a new category for services handled by your office.</p>
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

            <form action="{{ route('office.service-categories.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="max-w-xl">
                    <label class="form-label" for="name">Category Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control-base" required>
                </div>

                <div class="flex flex-wrap gap-3">
                    <x-office.actions.button variant="green" type="submit">Create Category</x-office.actions.button>
                    <x-office.actions.button :href="route('office.service-categories.index')" variant="white">Cancel</x-office.actions.button>
                </div>
            </form>
        </section>
    </div>
@endsection
