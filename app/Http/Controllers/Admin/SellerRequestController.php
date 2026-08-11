<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\SellerRequest;
use Illuminate\Support\Str;

class SellerRequestController extends Controller
{
    public function index()
    {
        $requests = SellerRequest::with('seller')->latest()->get();

        return view('admin.sellerRequests', compact('requests'));
    }

    public function approve(SellerRequest $request)
    {
        if ($request->request_type === 'category') {
            $name = $request->details['name'] ?? null;

            if ($name) {
                $vendor = \App\Models\Vendor::where('user_id', $request->seller_id)->first();

                Category::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'vendor_id' => $vendor?->id,
                        'name' => $name,
                        'description' => $request->details['description'] ?? null,
                        'is_active' => true,
                    ]
                );
            }
        } elseif ($request->request_type === 'coupon') {
            $code = $request->details['code'] ?? null;

            if ($code) {
                $vendor = \App\Models\Vendor::where('user_id', $request->seller_id)->first();

                Coupon::firstOrCreate(
                    ['code' => strtoupper($code)],
                    [
                        'vendor_id' => $vendor?->id,
                        'type' => ($request->details['discount_type'] ?? 'percentage') === 'percentage' ? 'percent' : 'fixed',
                        'value' => $request->details['discount_value'] ?? 0,
                        'min_order_amount' => $request->details['min_order_amount'] ?? null,
                        'max_uses' => $request->details['max_uses'] ?? null,
                        'starts_at' => $request->details['starts_at'] ?? null,
                        'expires_at' => $request->details['expires_at'] ?? null,
                        'is_active' => (bool) ($request->details['is_active'] ?? true),
                    ]
                );
            }
        }

        $request->update([
            'status' => 'approved',
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.seller.requests')->with('success', 'Request approved.');
    }

    public function reject(SellerRequest $request)
    {
        $request->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.seller.requests')->with('success', 'Request rejected.');
    }
}
