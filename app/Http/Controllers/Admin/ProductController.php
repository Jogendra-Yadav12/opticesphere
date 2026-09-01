<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $query = Product::with(['images', 'categories', 'vendor.user']);

        $isSeller = auth()->user()->role !== 'admin';

        if ($isSeller) {
            $query->where('vendor_id', $this->sellerVendor()->id);
        }

        $products = $query->latest()->get();

        $plan = null;
        $productLimit = null;
        $productCount = null;
        if ($isSeller) {
            $plan = auth()->user()->currentPlan();
            $productLimit = $plan?->product_limit;
            $productCount = $this->sellerVendor()->products()->count();
        }

        return view('admin.product', compact('products', 'plan', 'productLimit', 'productCount'));
    }

    public function create()
    {
        if ($this->productLimitReached()) {
            return redirect()->route('seller.product.index')->withErrors([
                'product_limit' => $this->productLimitMessage(),
            ]);
        }

        $categories = Category::orderBy('name')->get();
        $attributes = collect();

        return view('admin.addProduct', compact('categories', 'attributes'));
    }

    public function store(Request $request)
    {
        if ($this->productLimitReached()) {
            return back()->withErrors([
                'product_limit' => $this->productLimitMessage(),
            ])->withInput();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'special_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'variants' => 'nullable|array',
        ]);

        $isAdmin = auth()->user()->role === 'admin';

        $product = Product::create([
            'vendor_id' => $isAdmin ? $this->officialStore()->id : $this->sellerVendor()->id,
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')).'-'.Str::random(6),
            'sku' => 'SKU-'.strtoupper(Str::random(8)),
            'short_description' => Str::limit($request->input('description'), 120),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'special_price' => $request->filled('special_price') ? $request->input('special_price') : null,
            'stock_quantity' => $request->integer('stock'),
            'low_stock_threshold' => 5,
            'status' => 'active',
            'approval_status' => $isAdmin ? 'approved' : 'pending',
            'is_featured' => false,
            'is_taxable' => true,
            'approved_by' => $isAdmin ? auth()->id() : null,
            'approved_at' => $isAdmin ? now() : null,
        ]);

        $product->categories()->sync([$request->integer('category_id')]);

        $this->saveMainImage($product, $request);
        $this->saveGallery($product, $request);
        $this->saveVariants($product, $request);
        $this->syncProductAttributeValues($product, $request);
        $this->syncAttributeRequired($product, $request);

        return redirect()->route(($isAdmin ? 'admin' : 'seller').'.product.index')
            ->with('success', $isAdmin ? 'Product published.' : 'Product submitted for admin approval.');
    }

    public function edit(Product $product)
    {
        $this->authorizeProduct($product);

        $product->load(['images', 'variants.attributeValues', 'categories', 'attributeValues']);

        $categories = Category::orderBy('name')->get();
        $attributeIds = $product->attributeValues->pluck('attribute_id')->unique()->values();
        $attributes = Attribute::with('values')->whereIn('id', $attributeIds)->orderBy('name')->get();

        $vendors = collect();
        if (auth()->user()->role === 'admin') {
            $vendors = Vendor::with('user')->orderBy('store_name')->get();
            if ($product->vendor_id && $vendors->where('id', $product->vendor_id)->isEmpty()) {
                $vendors->push($product->vendor);
            }
        }

        return view('admin.editProduct', compact('product', 'categories', 'attributes', 'vendors'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'special_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'variants' => 'nullable|array',
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);

        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->short_description = Str::limit($request->input('description'), 120);
        $product->price = $request->input('price');
        $product->special_price = $request->filled('special_price') ? $request->input('special_price') : null;
        $product->stock_quantity = $request->integer('stock');

        if (auth()->user()->role === 'admin') {
            if ($request->filled('vendor_id')) {
                $product->vendor_id = $request->integer('vendor_id');
            }

            $product->approval_status = 'approved';
            $product->approved_by = auth()->id();
            $product->approved_at = now();
        }

        $product->save();

        $product->categories()->sync([$request->integer('category_id')]);

        if ($request->hasFile('image')) {
            $this->saveMainImage($product, $request);
        }

        if ($request->hasFile('gallery')) {
            $this->saveGallery($product, $request);
        }

        if ($request->has('variants')) {
            $product->variants()->delete();
            $this->saveVariants($product, $request);
        }

        $this->syncProductAttributeValues($product, $request);
        $this->syncAttributeRequired($product, $request);

        $prefix = auth()->user()->role === 'admin' ? 'admin' : 'seller';

        return redirect()->route($prefix.'.product.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        $product->delete();

        $prefix = auth()->user()->role === 'admin' ? 'admin' : 'seller';

        return redirect()->route($prefix.'.product.index')->with('success', 'Product deleted successfully.');
    }

    public function approve(Product $product)
    {
        $product->update([
            'approval_status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.product.index')->with('success', 'Product approved.');
    }

    public function reject(Product $product)
    {
        $product->update([
            'approval_status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.product.index')->with('success', 'Product rejected.');
    }

    private function authorizeProduct(Product $product): void
    {
        if (auth()->user()->role !== 'admin' && $product->vendor_id !== $this->sellerVendor()->id) {
            abort(403);
        }
    }

    private function productLimitReached(): bool
    {
        if (auth()->user()->role === 'admin') {
            return false;
        }

        $plan = auth()->user()->currentPlan();

        if (! $plan || $plan->product_limit <= 0) {
            return false;
        }

        return $this->sellerVendor()->products()->count() >= $plan->product_limit;
    }

    private function productLimitMessage(): string
    {
        $plan = auth()->user()->currentPlan();

        return "You have reached the maximum of {$plan->product_limit} products allowed on the {$plan->name} plan. Please upgrade your plan to add more products.";
    }

    private function sellerVendor(): Vendor
    {
        return auth()->user()->vendor ?? Vendor::create([
            'user_id' => auth()->id(),
            'store_name' => auth()->user()->name,
            'slug' => Str::slug(auth()->user()->name).'-'.Str::random(4),
            'status' => 'pending',
            'commission_rate' => 0,
            'commission_type' => 'percentage',
        ]);
    }

    private function officialStore(): Vendor
    {
        return auth()->user()->vendor ?? Vendor::create([
            'user_id' => auth()->id(),
            'store_name' => 'Official Store',
            'slug' => 'official-store',
            'status' => 'approved',
            'commission_rate' => 0,
            'commission_type' => 'percentage',
        ]);
    }

    private function saveMainImage(Product $product, Request $request): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        $filename = 'p-'.time().'-'.Str::random(6).'.'.$request->file('image')->getClientOriginalExtension();
        $request->file('image')->storeAs('images/products', $filename, config('filesystems.default'));

        $product->images()->where('is_primary', true)->delete();

        ProductImage::create([
            'product_id' => $product->id,
            'path' => $filename,
            'is_primary' => true,
        ]);
    }

    private function saveGallery(Product $product, Request $request): void
    {
        foreach ($request->file('gallery', []) as $file) {
            $filename = 'pg-'.time().'-'.Str::random(6).'.'.$file->getClientOriginalExtension();
            $file->storeAs('images/products/gallery', $filename, config('filesystems.default'));

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $filename,
                'is_primary' => false,
            ]);
        }
    }

    private function saveVariants(Product $product, Request $request): void
    {
        foreach ($request->input('variants', []) as $variant) {
            if (empty($variant['name'])) {
                continue;
            }

            $variantSku = $variant['sku'] ?? null;
            if (blank($variantSku)) {
                $variantSku = $product->sku.'-'.strtoupper(Str::slug($variant['name'], '-'));
            }

            $variantRow = ProductVariant::create([
                'product_id' => $product->id,
                'name' => $variant['name'],
                'sku' => $variantSku,
                'price' => isset($variant['price']) && $variant['price'] !== '' ? $variant['price'] : 0,
                'stock_quantity' => $variant['stock'] ?? 0,
                'status' => 'active',
            ]);

            if (! empty($variant['attribute_value_ids'])) {
                $variantRow->attributeValues()->sync(array_filter($variant['attribute_value_ids']));
            }
        }
    }

    private function syncProductAttributeValues(Product $product, Request $request): void
    {
        $valueIds = array_values(array_filter((array) $request->input('product_attribute_values', [])));
        $prices = $request->input('option_price', []);

        $pivots = [];
        foreach ($valueIds as $id) {
            $pivots[(int) $id] = [
                'price_adjustment' => isset($prices[$id]) && $prices[$id] !== '' ? (float) $prices[$id] : null,
            ];
        }

        $product->attributeValues()->sync($pivots);
    }

    private function syncAttributeRequired(Product $product, Request $request): void
    {
        $product->unsetRelation('attributeValues');
        $attributeIds = $product->attributeValues->pluck('attribute_id')->unique()->values()->all();
        $requiredIds = array_map('intval', (array) $request->input('attribute_required', []));

        Attribute::whereIn('id', $attributeIds)->update(['is_required' => false]);
        if ($requiredIds) {
            Attribute::whereIn('id', array_intersect($requiredIds, $attributeIds))->update(['is_required' => true]);
        }
    }
}
