<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function requestCategory()
    {
        return view('seller.requestCategory');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        SellerRequest::create([
            'seller_id' => auth()->id(),
            'request_type' => 'category',
            'details' => [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ],
            'status' => 'pending',
        ]);

        return redirect()->route('seller.request.category')->with('success', 'Category request submitted for review.');
    }

    public function requestCoupon()
    {
        return view('seller.requestCoupon');
    }

    public function storeCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'is_active' => 'required|in:0,1',
        ]);

        SellerRequest::create([
            'seller_id' => auth()->id(),
            'request_type' => 'coupon',
            'details' => [
                'code' => strtoupper($request->input('code')),
                'discount_type' => $request->input('discount_type'),
                'discount_value' => $request->input('discount_value'),
                'min_order_amount' => $request->filled('min_order_amount') ? $request->input('min_order_amount') : null,
                'max_uses' => $request->filled('max_uses') ? $request->integer('max_uses') : null,
                'starts_at' => $request->filled('starts_at') ? $request->input('starts_at') : null,
                'expires_at' => $request->filled('expires_at') ? $request->input('expires_at') : null,
                'is_active' => (bool) $request->integer('is_active'),
            ],
            'status' => 'pending',
        ]);

        return redirect()->route('seller.request.coupon')->with('success', 'Coupon request submitted for review.');
    }
}
