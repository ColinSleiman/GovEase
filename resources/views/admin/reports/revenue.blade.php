@extends('layouts.admin')

@section('title', 'Revenue Reports | GovEase Admin')

@section('content')
    <div class="admin-page">
        <section class="admin-page-header">
            <div>
                <h1 class="admin-page-title">Revenue Reports</h1>
                <p class="admin-page-subtitle">Review completed Stripe payments and revenue by office.</p>
            </div>
        </section>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="card-padded">
                <p class="text-xs uppercase tracking-wide text-slate-500">Completed Revenue</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">${{ number_format($summaryRevenue, 2) }}</p>
            </div>
            <div class="card-padded">
                <p class="text-xs uppercase tracking-wide text-slate-500">Completed Payments</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $payments->total() }}</p>
            </div>
        </div>

        <section class="card-padded">
            <form method="GET" action="{{ route('admin.reports.revenue') }}" class="grid gap-4 md:grid-cols-[minmax(0,1fr)_auto]">
                <div>
                    <label class="form-label">Office</label>
                    <select name="office_id" class="form-control-base">
                        <option value="">All offices</option>
                        @foreach ($offices as $office)
                            <option value="{{ $office->id }}" @selected((string) $filters['office_id'] === (string) $office->id)>{{ $office->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-3">
                    <x-admin.actions.button variant="blue" type="submit">Apply Filter</x-admin.actions.button>
                    <x-admin.actions.button :href="route('admin.reports.revenue')" variant="white">Reset</x-admin.actions.button>
                </div>
            </form>
        </section>

        <section class="card-padded">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Revenue by Office</h2>
                    <p class="card-subtitle">Totals based on completed payments.</p>
                </div>
            </div>
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead class="admin-table-head">
                        <tr>
                            <th class="admin-table-th">Office</th>
                            <th class="admin-table-th">Municipality</th>
                            <th class="admin-table-th">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="admin-table-body">
                        @forelse ($officeRevenue as $row)
                            <tr class="admin-table-row">
                                <td class="admin-table-td">{{ $row->name }}</td>
                                <td class="admin-table-td">{{ $row->municipality?->name ?? '-' }}</td>
                                <td class="admin-table-td">${{ number_format((float) ($row->revenue_total ?? 0), 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="admin-empty">No revenue data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="admin-table-wrap">
            <div class="card-padded">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">Completed Payments</h2>
                        <p class="card-subtitle">Use pagination to review transactions in manageable pages.</p>
                    </div>
                </div>
                <div class="admin-table-scroll">
                    <table class="admin-table">
                        <thead class="admin-table-head">
                            <tr>
                                <th class="admin-table-th">Reference</th>
                                <th class="admin-table-th">Office</th>
                                <th class="admin-table-th">Request #</th>
                                <th class="admin-table-th">Amount</th>
                                <th class="admin-table-th">Paid At</th>
                            </tr>
                        </thead>
                        <tbody class="admin-table-body">
                            @forelse ($payments as $payment)
                                <tr class="admin-table-row">
                                    <td class="admin-table-td">{{ $payment->transaction_reference }}</td>
                                    <td class="admin-table-td">{{ $payment->request?->service?->office?->name ?? '-' }}</td>
                                    <td class="admin-table-td">#{{ $payment->request_id }}</td>
                                    <td class="admin-table-td">${{ number_format((float) $payment->amount, 2) }}</td>
                                    <td class="admin-table-td">{{ $payment->created_at?->format('M d, Y h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="admin-empty">No completed payments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <div class="card-padded">
            {{ $payments->links() }}
        </div>
    </div>
@endsection
