<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ReviewReply;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'reviewable'])->latest()->get();

        return view('admin.reviews', compact('reviews'));
    }

    public function edit(Review $review)
    {
        return view('admin.editReview', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $data = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'title' => 'nullable|string|max:255',
            'body' => 'required|string|max:5000',
            'status' => 'required|in:approved,pending,rejected',
        ]);

        $review->update($data);
        $this->recalculateReviewableRating($review->reviewable);

        return redirect()->route('admin.review.index')->with('success', 'Review updated.');
    }

    public function approve(Review $review)
    {
        $review->status = 'approved';
        $review->save();
        $this->recalculateReviewableRating($review->reviewable);

        return redirect()->route('admin.review.index')->with('success', 'Review approved.');
    }

    public function reject(Review $review)
    {
        $review->status = 'rejected';
        $review->save();
        $this->recalculateReviewableRating($review->reviewable);

        return redirect()->route('admin.review.index')->with('success', 'Review rejected.');
    }

    public function reply(Request $request, Review $review)
    {
        $data = $request->validate(['body' => 'required|string|max:2000']);

        ReviewReply::create([
            'review_id' => $review->id,
            'replier_type' => get_class(auth()->user()),
            'replier_id' => auth()->id(),
            'body' => $data['body'],
        ]);

        return back()->with('success', 'Reply posted.');
    }

    public function destroy(Review $review)
    {
        $reviewable = $review->reviewable;
        $review->delete();
        $this->recalculateReviewableRating($reviewable);

        return redirect()->route('admin.review.index')->with('success', 'Review deleted.');
    }

    protected function recalculateReviewableRating($reviewable): void
    {
        if (! $reviewable || ! in_array('rating_avg', $reviewable->getFillable(), true)) {
            return;
        }

        $avg = $reviewable->reviews()->where('status', 'approved')->avg('rating');
        $reviewable->update(['rating_avg' => $avg ? round((float) $avg, 1) : 0]);
    }
}
