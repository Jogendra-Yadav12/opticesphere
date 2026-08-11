<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;

        abort_if(! $vendor, 404);

        $categories = Category::with('vendor')
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->get();

        return view('seller.categories', compact('categories'));
    }
}
