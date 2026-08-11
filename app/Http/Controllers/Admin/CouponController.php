<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Vendor;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::with('vendor')->latest()->get();

        return view('admin.coupons', compact('coupons'));
    }

    public function create()
    {
        $vendors = Vendor::with('user')->get();

        return view('admin.createCoupon', compact('vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'vendor_id' => 'nullable|exists:vendors,id',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'is_active' => 'required|in:0,1',
        ]);

        Coupon::create([
            'vendor_id' => $request->filled('vendor_id') ? $request->integer('vendor_id') : null,
            'code' => strtoupper($request->input('code')),
            'type' => $request->input('discount_type') === 'fixed' ? 'fixed' : 'percent',
            'value' => $request->input('discount_value'),
            'min_order_amount' => $request->filled('min_order_amount') ? $request->input('min_order_amount') : null,
            'max_uses' => $request->filled('max_uses') ? $request->integer('max_uses') : null,
            'starts_at' => $request->filled('starts_at') ? $request->input('starts_at') : null,
            'expires_at' => $request->filled('expires_at') ? $request->input('expires_at') : null,
            'is_active' => (bool) $request->integer('is_active'),
        ]);

        return redirect()->route('admin.coupon.index')->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        $vendors = Vendor::with('user')->get();

        return view('admin.editCoupon', compact('coupon', 'vendors'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,'.$coupon->id,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'vendor_id' => 'nullable|exists:vendors,id',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'is_active' => 'required|in:0,1',
        ]);

        $coupon->update([
            'vendor_id' => $request->filled('vendor_id') ? $request->integer('vendor_id') : null,
            'code' => strtoupper($request->input('code')),
            'type' => $request->input('discount_type') === 'fixed' ? 'fixed' : 'percent',
            'value' => $request->input('discount_value'),
            'min_order_amount' => $request->filled('min_order_amount') ? $request->input('min_order_amount') : null,
            'max_uses' => $request->filled('max_uses') ? $request->integer('max_uses') : null,
            'starts_at' => $request->filled('starts_at') ? $request->input('starts_at') : null,
            'expires_at' => $request->filled('expires_at') ? $request->input('expires_at') : null,
            'is_active' => (bool) $request->integer('is_active'),
        ]);

        return redirect()->route('admin.coupon.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupon.index')->with('success', 'Coupon deleted successfully.');
    }
}
