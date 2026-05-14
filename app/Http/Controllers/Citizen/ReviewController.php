<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Request as CitizenRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $pendingRequests = CitizenRequest::query()
            ->with(['service.office', 'service.serviceCategory', 'status'])
            ->where('user_id', $userId)
            ->whereHas('status', fn ($query) => $query->where('name', 'Completed'))
            ->doesntHave('review')
            ->latest()
            ->paginate(10, ['*'], 'pending_page')
            ->withQueryString();

        $reviews = Review::query()
            ->with(['office', 'service', 'request'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10, ['*'], 'reviews_page')
            ->withQueryString();

        return view('citizen.reviews.index', [
            'pendingRequests' => $pendingRequests,
            'reviews' => $reviews,
        ]);
    }

    public function create(CitizenRequest $request)
    {
        $request->load(['service.office', 'service.serviceCategory', 'status', 'review']);
        $this->authorizeReviewRequest($request);

        abort_if($request->review()->exists(), 403, 'This request has already been reviewed.');

        return view('citizen.reviews.create', [
            'requestData' => $request,
        ]);
    }

    public function store(Request $httpRequest)
    {
        $validated = $httpRequest->validate([
            'request_id' => ['required', 'exists:requests,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        $request = CitizenRequest::query()
            ->with(['service.office', 'service.serviceCategory', 'status', 'review'])
            ->findOrFail($validated['request_id']);

        $this->authorizeReviewRequest($request);

        abort_if($request->review()->exists(), 403, 'This request has already been reviewed.');

        Review::create([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'user_id' => Auth::id(),
            'office_id' => $request->service?->office_id,
            'request_id' => $request->id,
            'service_id' => $request->service_id,
        ]);

        return redirect()
            ->route('citizen.reviews.index')
            ->with('success', 'Thank you. Your review has been submitted.');
    }

    public function edit(Review $review)
    {
        $this->authorizeReview($review);

        return view('citizen.reviews.edit', [
            'review' => $review->load(['office', 'service', 'request']),
        ]);
    }

    public function update(Request $httpRequest, Review $review)
    {
        $this->authorizeReview($review);

        $validated = $httpRequest->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        $review->update($validated);

        return redirect()
            ->route('citizen.reviews.index')
            ->with('success', 'Your review has been updated.');
    }

    private function authorizeReviewRequest(CitizenRequest $request): void
    {
        abort_if((int) $request->user_id !== (int) Auth::id(), 403);
        abort_if(strtolower((string) $request->status?->name) !== 'completed', 403, 'Only completed requests can be reviewed.');
    }

    private function authorizeReview(Review $review): void
    {
        abort_if((int) $review->user_id !== (int) Auth::id(), 403);
    }
}
