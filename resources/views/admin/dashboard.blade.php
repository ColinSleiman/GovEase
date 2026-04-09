@extends('layouts.admin')

@section('title', 'Admin Dashboard | GovEase')

@section('content')
    <div class="space-y-6">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-bold text-slate-900">Admin Dashboard</h1>
            <p class="mt-2 text-sm text-slate-600">
                Welcome to GovEase administration. Use the quick links below to manage offices, municipalities, users,
                services, and reports.
            </p>
        </section>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <x-admin.section-card
                title="Offices Management"
                description="Create and review office records."
            >
                <a href="{{ route('admin.offices.create') }}" class="block rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    Create Office
                </a>
                <a href="{{ route('admin.offices.index') }}" class="block rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    View All Offices
                </a>
            </x-admin.section-card>

            <x-admin.section-card
                title="Municipalities Management"
                description="Maintain municipality details and records."
            >
                <a href="{{ route('admin.municipalities.create') }}" class="block rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    Create Municipality
                </a>
                <a href="{{ route('admin.municipalities.index') }}" class="block rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    View All Municipalities
                </a>
            </x-admin.section-card>

            <x-admin.section-card
                title="Users Management"
                description="Handle municipality user onboarding and status."
            >
                <a href="{{ route('admin.users.create') }}" class="block rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    Create Municipality User
                </a>
                <a href="{{ route('admin.users.index') }}" class="block rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    Manage / Activate / Deactivate Users
                </a>
            </x-admin.section-card>

            <x-admin.section-card
                title="Services Monitoring"
                description="Track incoming requests and service health."
            >
                <a href="{{ route('admin.requests.index') }}" class="block rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    View Incoming Requests
                </a>
                <a href="{{ route('admin.services.monitor') }}" class="block rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    Monitor Services
                </a>
            </x-admin.section-card>

            <x-admin.section-card
                title="Reports &amp; Analytics"
                description="Analyze office performance and revenue trends."
            >
                <a href="{{ route('admin.reports.office-requests') }}" class="block rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    Requests per Office
                </a>
                <a href="{{ route('admin.reports.revenue') }}" class="block rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    Revenue Reports
                </a>
            </x-admin.section-card>
        </section>
    </div>
@endsection
