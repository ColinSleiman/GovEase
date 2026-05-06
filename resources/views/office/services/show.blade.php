@extends('layouts.office')

@section('title', 'Service Details | GovEase Office')

@section('content')
    <div class="admin-page">
        <section class="admin-page-header">
            <div>
                <h1 class="admin-page-title">Service Details</h1>
                <p class="admin-page-subtitle">Review current service information.</p>
            </div>
            <div class="flex gap-3">
                <x-office.actions.button :href="route('office.services.edit', $row->id)" variant="blue">Edit Service</x-office.actions.button>
                <x-office.actions.button :href="route('office.services.index')" variant="white">Back to Services</x-office.actions.button>
            </div>
        </section>

        <section class="admin-table-wrap">
            <dl class="divide-y divide-slate-200">
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]">
                    <dt class="text-sm font-semibold text-slate-600">ID</dt>
                    <dd class="text-sm text-slate-900">{{ $row->id }}</dd>
                </div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]">
                    <dt class="text-sm font-semibold text-slate-600">Name</dt>
                    <dd class="text-sm text-slate-900">{{ $row->name }}</dd>
                </div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]">
                    <dt class="text-sm font-semibold text-slate-600">Category</dt>
                    <dd class="text-sm text-slate-900">{{ $row->serviceCategory?->name ?? '-' }}</dd>
                </div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]">
                    <dt class="text-sm font-semibold text-slate-600">Description</dt>
                    <dd class="text-sm text-slate-900">{{ $row->description }}</dd>
                </div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]">
                    <dt class="text-sm font-semibold text-slate-600">Price</dt>
                    <dd class="text-sm text-slate-900">{{ $row->price }}</dd>
                </div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]">
                    <dt class="text-sm font-semibold text-slate-600">Duration</dt>
                    <dd class="text-sm text-slate-900">{{ $row->duration }} minutes</dd>
                </div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]">
                    <dt class="text-sm font-semibold text-slate-600">Created At</dt>
                    <dd class="text-sm text-slate-900">{{ $row->created_at }}</dd>
                </div>
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr]">
                    <dt class="text-sm font-semibold text-slate-600">Updated At</dt>
                    <dd class="text-sm text-slate-900">{{ $row->updated_at }}</dd>
                </div>
            </dl>
        </section>
    </div>
@endsection
