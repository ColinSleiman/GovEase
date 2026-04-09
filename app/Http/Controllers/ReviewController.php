<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Http\Response;

class ReviewController extends Controller
{
    public function index() {
        $reviews = Review::with(['user', 'office'])->get();
        return response()->json($reviews, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|integer',
            'comment' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'office_id' => 'required|exists:offices,id',
        ]);

        $review = Review::create($validated);

        return response()->json(
            $review->load(['user', 'office']),
            Response::HTTP_CREATED
        );
    }

    public function show(Review $review)
    {
        return response()->json(
            $review->load(['user', 'office']),
            Response::HTTP_OK
        );
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'rating' => 'sometimes|required|integer',
            'comment' => 'sometimes|required|string',
            'user_id' => 'sometimes|required|exists:users,id',
            'office_id' => 'sometimes|required|exists:offices,id',
        ]);

        $review->update($validated);

        return response()->json(
            $review->load(['user', 'office']),
            Response::HTTP_OK
        );
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
