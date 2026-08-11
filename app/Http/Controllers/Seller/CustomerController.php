<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;

        if (! $vendor) {
            abort(404);
        }

        $customers = User::where('role', 'customer')
            ->whereHas('orders.items', fn ($q) => $q->where('vendor_id', $vendor->id))
            ->with(['orders' => fn ($q) => $q->whereHas('items', fn ($i) => $i->where('vendor_id', $vendor->id))])
            ->latest()
            ->get();

        return view('seller.customers', compact('customers'));
    }
}
