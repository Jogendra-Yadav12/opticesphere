<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Newsletter;
use App\Models\Page;
use App\Models\Product;
use App\Models\Review;
use App\Models\Vendor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private function featuredVendors()
    {
        return Vendor::approved()
            ->hasActivePlan()
            ->orderBy('id')
            ->take(16)
            ->get();
    }

    private function wishlistIds()
    {
        if (! auth()->check()) {
            return collect();
        }

        return \App\Models\Wishlist::where('user_id', auth()->id())->pluck('product_id');
    }

    public function index()
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with('children')
            ->get();

        $featuredProducts = Product::visible()
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $vendors = $this->featuredVendors();

        $wishlistIds = $this->wishlistIds();

        $nonPurchasableVendorIds = Product::nonPurchasableVendorIds();

        return view('home', compact('banners', 'categories', 'featuredProducts', 'vendors', 'wishlistIds', 'nonPurchasableVendorIds'));
    }

    public function shop(Request $request)
    {
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with('children')
            ->get();

        $query = Product::visible();

        $selectedCategory = null;
        if ($request->filled('category_id')) {
            $selectedCategory = Category::with('parent')->find($request->integer('category_id'));
            $categoryIds = collect([$selectedCategory->id]);
            $childIds = Category::whereIn('parent_id', $categoryIds)->pluck('id');
            while ($childIds->isNotEmpty()) {
                $categoryIds = $categoryIds->merge($childIds);
                $childIds = Category::whereIn('parent_id', $childIds)->pluck('id');
            }
            $query->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $categoryIds->all()));
        }

        if ($request->filled('q')) {
            $query->where(fn ($q) => $q->where('name', 'like', '%'.$request->string('q').'%')
                ->orWhere('description', 'like', '%'.$request->string('q').'%'));
        }

        $boundsQuery = (clone $query);
        $minPrice = (int) floor((float) $boundsQuery->min('price'));
        $maxPrice = (int) ceil(max((float) $boundsQuery->max('price'), (float) $boundsQuery->max('special_price')));

        if ($request->filled('min_price') || $request->filled('max_price')) {
            $min = (float) $request->input('min_price', $minPrice);
            $max = (float) $request->input('max_price', $maxPrice);
            $query->where(function ($q) use ($min, $max) {
                $q->whereBetween('special_price', [$min, $max])
                    ->orWhere(fn ($q2) => $q2->whereNull('special_price')->whereBetween('price', [$min, $max]));
            });
        }

        $sortKey = $request->input('sort', 'default');
        $order = $request->input('order', 'asc');

        if ($sortKey === 'name') {
            $query->orderBy('name', $order === 'desc' ? 'desc' : 'asc');
        } elseif ($sortKey === 'price') {
            $query->orderByRaw('COALESCE(special_price, price) '.($order === 'desc' ? 'desc' : 'asc'));
        } elseif ($sortKey === 'newest') {
            $query->latest();
        } elseif ($sortKey === 'oldest') {
            $query->oldest();
        }

        $perPage = (int) $request->input('limit', 12);
        if (! in_array($perPage, [12, 24, 36, 48, 60])) {
            $perPage = 12;
        }

        $products = $query->with('images')->paginate($perPage)->withQueryString();

        $latestProducts = Product::visible()
            ->latest()
            ->take(3)
            ->get();

        $wishlistIds = $this->wishlistIds();

        $nonPurchasableVendorIds = Product::nonPurchasableVendorIds();

        return view('shop', compact('categories', 'products', 'selectedCategory', 'minPrice', 'maxPrice', 'latestProducts', 'wishlistIds', 'nonPurchasableVendorIds'));
    }

    public function productDetails($id)
    {
        $product = Product::visible()->with(['images', 'variants.attributeValues.attribute', 'categories', 'brand', 'vendor', 'attributeValues.attribute', 'reviews' => fn ($q) => $q->where('status', 'approved')->with('user')])
            ->findOrFail($id);

        $category = $product->categories->first();
        $avgRating = round((float) $product->reviews->avg('rating'), 1);
        $shippingMethods = \App\Models\ShippingMethod::where('is_active', true)->get();

        $productAttributes = $product->attributeValues
            ->concat($product->variants->flatMap->attributeValues)
            ->unique('id')
            ->groupBy(fn ($value) => $value->attribute->name ?? 'Other')
            ->map(fn ($group) => $group->unique('id')->values())
            ->sortKeys();

        $inWishlist = auth()->check()
            && \App\Models\Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->exists();

        $nonPurchasableVendorIds = Product::nonPurchasableVendorIds();

        return view('prduct-details', compact('product', 'category', 'avgRating', 'shippingMethods', 'productAttributes', 'inWishlist', 'nonPurchasableVendorIds'));
    }

    public function productReview(Request $request, $id)
    {
        $product = Product::visible()->findOrFail($id);

        $data = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'title' => 'nullable|string|max:255',
            'body' => 'required|string|max:2000',
        ]);

        Review::create([
            'reviewable_type' => Product::class,
            'reviewable_id' => $product->id,
            'user_id' => auth()->id(),
            'rating' => $data['rating'],
            'title' => $data['title'] ?? null,
            'body' => $data['body'],
            'status' => 'pending',
        ]);

        return redirect()->route('customer.product.details', $product->id)->with('success', 'Thank you! Your review has been submitted and is awaiting approval.');
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)->where('status', 'published')->firstOrFail();

        return view('page', compact('page'));
    }

    public function newsletterSubscribe(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        Newsletter::updateOrCreate(
            ['email' => $data['email']],
            ['is_subscribed' => true, 'token' => \Illuminate\Support\Str::random(32)]
        );

        return redirect()->back()->with('success', 'Thanks for subscribing to our newsletter!');
    }

    public function aboutus()
    {
        $vendors = $this->featuredVendors();
        $sellersCount = Vendor::approved()->count();
        $productsCount = Product::visible()->count();
        $categoriesCount = Category::where('is_active', true)->count();

        return view('aboutus', compact('vendors', 'sellersCount', 'productsCount', 'categoriesCount'));
    }

    public function contactus()
    {
        return view('contactus');
    }

    public function store(Request $request, $slug)
    {
        $vendor = Vendor::approved()
            ->where(fn ($q) => $q->where('slug', $slug)->orWhere('id', (int) $slug))
            ->with(['reviews' => fn ($q) => $q->where('status', 'approved')->with(['user', 'replies.replier'])])
            ->firstOrFail();

        $products = $vendor->products()
            ->visible()
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $avgRating = round((float) $vendor->reviews->avg('rating'), 1);

        $wishlistIds = $this->wishlistIds();

        $nonPurchasableVendorIds = Product::nonPurchasableVendorIds();

        return view('store', compact('vendor', 'products', 'avgRating', 'wishlistIds', 'nonPurchasableVendorIds'));
    }

    public function storeReview(Request $request, $slug)
    {
        $vendor = Vendor::approved()
            ->where(fn ($q) => $q->where('slug', $slug)->orWhere('id', (int) $slug))
            ->firstOrFail();

        $data = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'title' => 'nullable|string|max:255',
            'body' => 'required|string|max:2000',
        ]);

        Review::create([
            'reviewable_type' => Vendor::class,
            'reviewable_id' => $vendor->id,
            'user_id' => auth()->id(),
            'rating' => $data['rating'],
            'title' => $data['title'] ?? null,
            'body' => $data['body'],
            'status' => 'pending',
        ]);

        return redirect()->route('store', $vendor->slug ?: $vendor->id)->with('success', 'Thank you! Your review has been submitted and is awaiting approval.');
    }
}
