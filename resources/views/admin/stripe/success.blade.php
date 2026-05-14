@extends('layouts.admin')

@section('title', 'Payment Successful | GovEase Admin')

@section('content')
    <div class="mx-auto max-w-xl">
        <section class="rounded-xl border border-slate-200 bg-white p-8 text-center shadow-sm">
            <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-slate-900">Payment Successful</h1>
            <p class="mt-3 text-sm text-slate-600">Your test payment has been processed successfully.</p>

            <div class="mt-6 rounded-lg border border-slate-200 bg-slate-50 p-4 text-left">
                <div class="flex justify-between text-sm text-slate-600">
                    <span>Status</span>
                    <span class="font-semibold text-emerald-700">Paid</span>
                </div>
                @if (request('session_id'))
                    <div class="mt-3 flex justify-between gap-4 text-sm text-slate-600">
                        <span>Session ID</span>
                        <span class="break-all font-mono text-xs text-slate-800">{{ request('session_id') }}</span>
                    </div>
                @endif
                <div class="mt-3 flex justify-between border-t border-slate-200 pt-3 text-base font-bold text-slate-900">
                    <span>Total Paid</span>
                    <span>$25.00</span>
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <x-admin.button :href="route('admin.dashboard')" variant="white">Back to Dashboard</x-admin.button>
                <x-admin.button :href="route('admin.stripe.test')" variant="blue">Make Another Payment</x-admin.button>
            </div>
        </section>
    </div>
@endsection
