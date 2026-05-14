@extends('layouts.admin')

@section('title', 'Citizen Reviews | GovEase Admin')

@section('content')
    <div class="admin-page">
        <section class="admin-page-header">
            <div>
                <h1 class="admin-page-title">Citizen Reviews</h1>
                <p class="admin-page-subtitle">View comments and ratings citizens placed for each office service.</p>
            </div>
        </section>

        <section class="card-padded">
            <form method="GET" action="{{ route('admin.reviews.index') }}" class="grid gap-4 md:grid-cols-4">
                <div>
                    <label class="form-label">Search</label>
                    <input type="text" name="search" value="{{ $filters['search'] }}" class="form-control-base" placeholder="Citizen, office, service, comment">
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
                    <label class="form-label">Service</label>
                    <select name="service_id" class="form-control-base">
                        <option value="">All services</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected((string) $filters['service_id'] === (string) $service->id)>{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Rating</label>
                    <select name="rating" class="form-control-base">
                        <option value="">All ratings</option>
                        @for ($rating = 5; $rating >= 1; $rating--)
                            <option value="{{ $rating }}" @selected((string) $filters['rating'] === (string) $rating)>{{ $rating }}/5</option>
                        @endfor
                    </select>
                </div>

                <div class="md:col-span-4 flex flex-wrap gap-3">
                    <x-admin.actions.button variant="blue" type="submit">Apply Filters</x-admin.actions.button>
                    <x-admin.actions.button :href="route('admin.reviews.index')" variant="white">Reset</x-admin.actions.button>
                </div>
            </form>
        </section>

        <section class="admin-table-wrap">
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead class="admin-table-head">
                        <tr>
                            <th class="admin-table-th">Citizen</th>
                            <th class="admin-table-th">Office</th>
                            <th class="admin-table-th">Service</th>
                            <th class="admin-table-th">Request #</th>
                            <th class="admin-table-th">Rating</th>
                            <th class="admin-table-th">Comment</th>
                            <th class="admin-table-th">Submitted</th>
                        </tr>
                    </thead>
                    <tbody class="admin-table-body">
                        @forelse ($rows as $row)
                            <tr class="admin-table-row">
                                <td class="admin-table-td">{{ $row->user?->full_name ?? '-' }}</td>
                                <td class="admin-table-td">{{ $row->office?->name ?? '-' }}</td>
                                <td class="admin-table-td">
                                    <div>{{ $row->service?->name ?? '-' }}</div>
                                    <div class="text-xs text-slate-500">{{ $row->service?->serviceCategory?->name ?? '-' }}</div>
                                </td>
                                <td class="admin-table-td">{{ $row->request?->tracking_number ?? '#' . $row->request_id }}</td>
                                <td class="admin-table-td">{{ $row->rating }}/5</td>
                                <td class="admin-table-td">{{ $row->comment }}</td>
                                <td class="admin-table-td">{{ $row->created_at?->format('M d, Y h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="admin-empty">No citizen reviews found yet.</td>
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
