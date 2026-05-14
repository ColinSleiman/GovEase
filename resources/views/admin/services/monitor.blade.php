@extends('layouts.admin')

@section('title', 'Services Monitor | GovEase Admin')

@section('content')
    <div class="admin-page">
        <section class="admin-page-header">
            <div>
                <h1 class="admin-page-title">Services Monitor</h1>
                <p class="admin-page-subtitle">Oversee service operations, request load, and completion progress by office.</p>
            </div>
        </section>

        <section class="card-padded">
            <form method="GET" action="{{ route('admin.services.monitor') }}" class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="form-label">Service Name</label>
                    <input type="text" name="search" value="{{ $filters['search'] }}" class="form-control-base" placeholder="Search services">
                </div>
                <div>
                    <label class="form-label">Office</label>
                    <select name="office_id" class="form-control-base">
                        <option value="">All offices</option>
                        @foreach ($offices as $office)
                            <option value="{{ $office->id }}" @selected((string) $filters['office_id'] === (string) $office->id)>{{ $office->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Service Category</label>
                    <select name="service_category_id" class="form-control-base">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $filters['service_category_id'] === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-3 flex flex-wrap gap-3">
                    <x-admin.actions.button variant="blue" type="submit">Apply Filters</x-admin.actions.button>
                    <x-admin.actions.button :href="route('admin.services.monitor')" variant="white">Reset</x-admin.actions.button>
                </div>
            </form>
        </section>

        <section class="admin-table-wrap">
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead class="admin-table-head">
                        <tr>
                            <th class="admin-table-th">Service</th>
                            <th class="admin-table-th">Office</th>
                            <th class="admin-table-th">Municipality</th>
                            <th class="admin-table-th">Category</th>
                            <th class="admin-table-th">Price</th>
                            <th class="admin-table-th">Requests</th>
                            <th class="admin-table-th">Pending</th>
                            <th class="admin-table-th">In Review</th>
                            <th class="admin-table-th">Completed</th>
                        </tr>
                    </thead>
                    <tbody class="admin-table-body">
                        @forelse ($rows as $row)
                            <tr class="admin-table-row">
                                <td class="admin-table-td">{{ $row->name }}</td>
                                <td class="admin-table-td">{{ $row->office?->name ?? '-' }}</td>
                                <td class="admin-table-td">{{ $row->office?->municipality?->name ?? '-' }}</td>
                                <td class="admin-table-td">{{ $row->serviceCategory?->name ?? '-' }}</td>
                                <td class="admin-table-td">${{ number_format((float) $row->price, 2) }}</td>
                                <td class="admin-table-td">{{ $row->requests_count }}</td>
                                <td class="admin-table-td">{{ $row->pending_requests_count }}</td>
                                <td class="admin-table-td">{{ $row->in_review_requests_count }}</td>
                                <td class="admin-table-td">{{ $row->completed_requests_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="admin-empty">No services found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="card-padded">
            {{ $rows->links() }}
        </div>
    </div>
@endsection
