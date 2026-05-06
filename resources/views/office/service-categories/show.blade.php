@extends('layouts.office')

@section('title', 'Service Category Details | GovEase Office')

@section('content')
    <div class="admin-page">
        <section class="admin-page-header">
            <div>
                <h1 class="admin-page-title">Service Category Details</h1>
                <p class="admin-page-subtitle">Review category information used by your office services.</p>
            </div>
            <div class="flex gap-3">
                <x-office.actions.button :href="route('office.service-categories.edit', $row->id)" variant="blue">Edit Category</x-office.actions.button>
                <x-office.actions.button :href="route('office.service-categories.index')" variant="white">Back to Categories</x-office.actions.button>
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
                    <dt class="text-sm font-semibold text-slate-600">Services Count</dt>
                    <dd class="text-sm text-slate-900">{{ $row->services_count }}</dd>
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
