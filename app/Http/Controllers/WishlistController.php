<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $items = auth()->user()->wishlists()
            ->with('product.images')
            ->whereHas('product', fn ($q) => $q->visible())
            ->latest()
            ->get();

        return view('wishlist', compact('items'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $request->integer('product_id'),
        ]);

        return redirect()->back()->with('success', 'Product added to your wishlist.');
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $item = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $request->integer('product_id'))
            ->first();

        if ($item) {
            $item->delete();
            $message = 'Product removed from your wishlist.';
            $added = false;
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $request->integer('product_id'),
            ]);
            $message = 'Product added to your wishlist.';
            $added = true;
        }

        if ($request->wantsJson()) {
            return response()->json(['added' => $added]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function remove(Wishlist $item)
    {
        if ($item->user_id !== auth()->id()) {
            abort(403);
        }

        $item->delete();

        return redirect()->route('wishlist')->with('success', 'Item removed from wishlist.');
    }
}
