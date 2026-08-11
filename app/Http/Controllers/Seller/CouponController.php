<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;

        abort_if(! $vendor, 404);

        $coupons = Coupon::with('vendor')->where('vendor_id', $vendor->id)->latest()->get();

        return view('seller.coupons', compact('coupons'));
    }
}
