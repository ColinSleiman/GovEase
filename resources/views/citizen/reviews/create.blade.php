@extends('layouts.citizen')

@section('title', 'Leave Review | GovEase')

@section('content')
    <div class="citizen-page">
        <section class="card-padded">
            <div class="card-header">
                <div>
                    <h1 class="citizen-page-title">Rate Office Service</h1>
                    <p class="citizen-page-subtitle">Share your experience for this completed request.</p>
                </div>
                <a href="{{ route('citizen.reviews.index') }}" class="btn-base btn-variant-white">Back to Reviews</a>
            </div>

            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
                <form action="{{ route('citizen.reviews.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="request_id" value="{{ $requestData->id }}">

                    <div>
                        <label for="rating" class="mb-2 block text-sm font-medium text-slate-700">Rating</label>
                        <select id="rating" name="rating" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm" required>
                            <option value="">Select rating</option>
                            @for ($rating = 5; $rating >= 1; $rating--)
                                <option value="{{ $rating }}" @selected((string) old('rating') === (string) $rating)>{{ $rating }} / 5</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label for="comment" class="mb-2 block text-sm font-medium text-slate-700">Comment and Feedback</label>
                        <textarea id="comment" name="comment" rows="6" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm" placeholder="Tell us about the quality, responsiveness, and overall service experience." required>{{ old('comment') }}</textarea>
                    </div>

                    <button type="submit" class="btn-base btn-variant-blue">Submit Review</button>
                </form>

                <aside class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                    <h2 class="text-lg font-semibold text-slate-900">Completed Request</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-4"><dt class="font-semibold text-slate-600">Tracking #</dt><dd class="text-right text-slate-900">{{ $requestData->tracking_number }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="font-semibold text-slate-600">Office</dt><dd class="text-right text-slate-900">{{ $requestData->service?->office?->name ?? '-' }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="font-semibold text-slate-600">Category</dt><dd class="text-right text-slate-900">{{ $requestData->service?->serviceCategory?->name ?? '-' }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="font-semibold text-slate-600">Service</dt><dd class="text-right text-slate-900">{{ $requestData->service?->name ?? '-' }}</dd></div>
                    </dl>
                </aside>
            </div>
        </section>
    </div>
@endsection
