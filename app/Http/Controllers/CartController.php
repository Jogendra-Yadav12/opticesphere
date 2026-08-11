<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = auth()->user()->cart;

        $items = $cart ? $cart->items()->with('product.images', 'product.vendor', 'product.attributeValues.attribute', 'variant.attributeValues.attribute')->get() : collect();

        $subtotal = $items->sum(fn ($item) => $item->unit_price * $item->quantity);

        $coupon = null;
        $discount = 0.0;
        $couponCode = session('coupon_code');

        if ($couponCode) {
            $coupon = Coupon::where('code', strtoupper($couponCode))->first();
            $applicableSubtotal = $coupon
                ? $items->filter(fn ($item) => $coupon->appliesToItem($item))->sum(fn ($item) => $item->unit_price * $item->quantity)
                : 0;

            if (! $coupon || $applicableSubtotal <= 0 || ! $coupon->isValid($applicableSubtotal)) {
                session()->forget('coupon_code');
                $coupon = null;
            } else {
                $discount = $coupon->calculateDiscount($applicableSubtotal);
            }
        }

        return view('shop-cart', compact('items', 'subtotal', 'coupon', 'discount'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);

        $code = strtoupper($request->input('coupon_code'));
        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            return redirect()->back()->with('error', 'Invalid coupon code.');
        }

        $cart = auth()->user()->cart;
        $items = $cart ? $cart->items()->with('product.vendor')->get() : collect();

        $applicableSubtotal = $items->filter(fn ($item) => $coupon->appliesToItem($item))
            ->sum(fn ($item) => $item->unit_price * $item->quantity);

        if ($coupon->isScopedToVendor() && $applicableSubtotal <= 0) {
            return redirect()->back()->with('error', 'This coupon is only valid for products from '.($coupon->vendor?->store_name ?? 'that store').'.');
        }

        if ($coupon->starts_at && $coupon->starts_at->isFuture()) {
            return redirect()->back()->with('error', 'This coupon is not active yet. It starts on '.$coupon->starts_at->format('d M Y, h:i A').'.');
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return redirect()->back()->with('error', 'This coupon has expired.');
        }

        if (! $coupon->isValid($applicableSubtotal)) {
            $reason = $coupon->min_order_amount !== null && $applicableSubtotal < (float) $coupon->min_order_amount
                ? 'This coupon requires a minimum order amount of ₹'.number_format((float) $coupon->min_order_amount, 2).'.'
                : 'This coupon is no longer valid.';
            return redirect()->back()->with('error', $reason);
        }

        session(['coupon_code' => $code]);

        return redirect()->back()->with('success', 'Coupon applied successfully.');
    }

    public function removeCoupon()
    {
        session()->forget('coupon_code');

        return redirect()->back()->with('success', 'Coupon removed.');
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
            'selected_attributes' => 'nullable',
        ]);

        $product = Product::visible()->findOrFail($request->integer('product_id'));

        if (in_array($product->vendor_id, Product::nonPurchasableVendorIds(), true)) {
            return redirect()->back()->with('error', 'This product is for display only and is not available for purchase.');
        }

        $quantity = $request->integer('quantity');
        $variantId = $request->integer('variant_id') ?: null;

        $selectedAttributesRaw = $request->input('selected_attributes', []);

        if (is_string($selectedAttributesRaw)) {
            $selectedAttributesRaw = json_decode($selectedAttributesRaw, true) ?? [];
        }

        $selectedAttributeIds = collect($selectedAttributesRaw)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->sort()
            ->values()
            ->all();

        $basePrice = $product->special_price ?? $product->price;

        if ($variantId) {
            $variant = $product->variants()->findOrFail($variantId);
            $unitPrice = $basePrice + ($variant->price ?? 0);
            $selectedAttributes = null;
        } elseif (! empty($selectedAttributeIds)) {
            $optionAdjustment = $product->attributeValues
                ->whereIn('id', $selectedAttributeIds)
                ->sum(fn ($value) => (float) ($value->pivot->price_adjustment ?? 0));
            $unitPrice = $basePrice + $optionAdjustment;
            $selectedAttributes = $selectedAttributeIds;
        } else {
            $unitPrice = $basePrice;
            $selectedAttributes = null;
        }

        $cart = auth()->user()->cart;

        if (! $cart) {
            $cart = Cart::create(['user_id' => auth()->id()]);
        }

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('variant_id', $variantId)
            ->where('selected_attributes', $selectedAttributes === null ? null : json_encode($selectedAttributes))
            ->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->unit_price = $unitPrice;
            $item->line_total = $item->quantity * $unitPrice;
            $item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'variant_id' => $variantId,
                'selected_attributes' => $selectedAttributes,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $unitPrice * $quantity,
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart.');
    }

    public function remove(CartItem $item)
    {
        if ($item->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $item->delete();

        return redirect()->route('cart')->with('success', 'Item removed from cart.');
    }
}
