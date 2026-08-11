<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ReviewReply;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;

        if (! $vendor) {
            abort(404);
        }

        $reviews = Review::with(['user', 'replies'])
            ->where('reviewable_type', get_class($vendor))
            ->where('reviewable_id', $vendor->id)
            ->latest()
            ->get();

        return view('seller.reviews', compact('vendor', 'reviews'));
    }

    public function reply(Request $request, Review $review)
    {
        $vendor = auth()->user()->vendor;

        abort_if(! $vendor || $review->reviewable_type !== get_class($vendor) || $review->reviewable_id !== $vendor->id, 404);

        $data = $request->validate(['body' => 'required|string|max:2000']);

        ReviewReply::create([
            'review_id' => $review->id,
            'replier_type' => get_class(auth()->user()),
            'replier_id' => auth()->id(),
            'body' => $data['body'],
        ]);

        return back()->with('success', 'Reply posted.');
    }
}
