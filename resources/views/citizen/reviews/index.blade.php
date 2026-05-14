@extends('layouts.citizen')

@section('title', 'My Reviews | GovEase')

@section('content')
    <div class="citizen-page">
        <section class="card-padded">
            <div class="card-header">
                <div>
                    <h1 class="citizen-page-title">Comments, Feedback, and Reviews</h1>
                    <p class="citizen-page-subtitle">Rate completed office services and manage the feedback you have already shared.</p>
                </div>
            </div>
        </section>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <section class="card-padded">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Completed Services Ready for Review</h2>
                    <p class="card-subtitle">Only completed requests can be reviewed.</p>
                </div>
            </div>
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead class="admin-table-head">
                    <tr>
                        <th class="admin-table-th">Tracking #</th>
                        <th class="admin-table-th">Office</th>
                        <th class="admin-table-th">Category</th>
                        <th class="admin-table-th">Service</th>
                        <th class="admin-table-th-right">Action</th>
                    </tr>
                    </thead>
                    <tbody class="admin-table-body">
                    @forelse ($pendingRequests as $row)
                        <tr class="admin-table-row">
                            <td class="admin-table-td">{{ $row->tracking_number }}</td>
                            <td class="admin-table-td">{{ $row->service?->office?->name ?? '-' }}</td>
                            <td class="admin-table-td">{{ $row->service?->serviceCategory?->name ?? '-' }}</td>
                            <td class="admin-table-td">{{ $row->service?->name ?? '-' }}</td>
                            <td class="admin-table-actions-cell">
                                <div class="admin-actions">
                                    <a href="{{ route('citizen.reviews.create', $row->id) }}" class="btn-base btn-variant-blue btn-xs">Review</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="admin-empty">No completed services are waiting for feedback.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $pendingRequests->links() }}
            </div>
        </section>

        <section class="card-padded">
            <div class="card-header">
                <div>
                    <h2 class="card-title">My Submitted Reviews</h2>
                    <p class="card-subtitle">Your comments and ratings for office services.</p>
                </div>
            </div>
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead class="admin-table-head">
                    <tr>
                        <th class="admin-table-th">Office</th>
                        <th class="admin-table-th">Service</th>
                        <th class="admin-table-th">Rating</th>
                        <th class="admin-table-th">Comment</th>
                        <th class="admin-table-th">Updated</th>
                        <th class="admin-table-th-right">Action</th>
                    </tr>
                    </thead>
                    <tbody class="admin-table-body">
                    @forelse ($reviews as $review)
                        <tr class="admin-table-row">
                            <td class="admin-table-td">{{ $review->office?->name ?? '-' }}</td>
                            <td class="admin-table-td">{{ $review->service?->name ?? '-' }}</td>
                            <td class="admin-table-td">{{ $review->rating }}/5</td>
                            <td class="admin-table-td">{{ $review->comment }}</td>
                            <td class="admin-table-td">{{ $review->updated_at?->format('M d, Y h:i A') }}</td>
                            <td class="admin-table-actions-cell">
                                <div class="admin-actions">
                                    <a href="{{ route('citizen.reviews.edit', $review->id) }}" class="btn-base btn-variant-white btn-xs">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="admin-empty">You have not submitted any reviews yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $reviews->links() }}
            </div>
        </section>
    </div>
@endsection
