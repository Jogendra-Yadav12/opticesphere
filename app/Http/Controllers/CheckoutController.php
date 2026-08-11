<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private function sellerConflictMessage($items): ?string
    {
        $groups = $items->groupBy(fn ($item) => $item->product->vendor_id ?? 0);

        if ($groups->count() <= 1) {
            return null;
        }

        $names = $groups->map(function ($group) {
            $vendor = $group->first()->product->vendor;

            return $vendor ? $vendor->store_name : 'Store Owner';
        })->implode(', ');

        return 'You can only buy products from one seller at a time. Your cart contains products from different sellers ('.$names.'). Please remove items from the other sellers and place a separate order for each seller.';
    }

    private function outOfStockMessage($items): ?string
    {
        $outOfStock = $items->filter(function ($item) {
            $available = (int) $item->product->stock_quantity;

            return $available <= 0 || $available < (int) $item->quantity;
        });

        if ($outOfStock->isEmpty()) {
            return null;
        }

        $names = $outOfStock->map(fn ($item) => $item->product->name)->unique()->implode(', ');

        return 'Some products in your cart are out of stock: '.$names.'. Please remove them or wait for restock before proceeding to checkout.';
    }

    public function index()
    {
        $cart = auth()->user()->cart;

        $items = $cart ? $cart->items()->with('product.images', 'product.vendor', 'product.attributeValues.attribute', 'variant.attributeValues.attribute')->get() : collect();

        if ($items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        if ($conflict = $this->sellerConflictMessage($items)) {
            return redirect()->route('cart')->with('error', $conflict);
        }

        if ($stockMessage = $this->outOfStockMessage($items)) {
            return redirect()->route('cart')->with('error', $stockMessage);
        }

        $addresses = auth()->user()->addresses()->orderByDesc('is_default')->get();

        $address = $addresses->first();

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

        return view('checkout', compact('items', 'address', 'addresses', 'subtotal', 'coupon', 'discount'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'address_id' => 'nullable|integer',
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'payment_method' => 'required|in:cash_on_delivery,online',
        ]);

        $cart = auth()->user()->cart;

        if (! $cart || $cart->items()->count() === 0) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $items = $cart->items()->with('product.vendor')->get();

        if ($conflict = $this->sellerConflictMessage($items)) {
            return redirect()->back()->withInput()->with('error', $conflict);
        }

        if ($stockMessage = $this->outOfStockMessage($items)) {
            return redirect()->back()->withInput()->with('error', $stockMessage);
        }

        $subtotal = $items->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });

        $coupon = null;
        $discount = 0.0;
        $couponCode = session('coupon_code');

        if ($couponCode) {
            $coupon = Coupon::where('code', strtoupper($couponCode))->first();
            $applicableSubtotal = $coupon
                ? $items->filter(fn ($item) => $coupon->appliesToItem($item))->sum(fn ($item) => $item->unit_price * $item->quantity)
                : 0;

            if ($coupon && $applicableSubtotal > 0 && $coupon->isValid($applicableSubtotal)) {
                $discount = $coupon->calculateDiscount($applicableSubtotal);
            } else {
                $coupon = null;
            }
        }

        $paymentMethod = $request->input('payment_method');

        $order = DB::transaction(function () use ($request, $items, $subtotal, $discount, $coupon, $paymentMethod, $cart) {
            $address = UserAddress::where('user_id', auth()->id())
                ->find($request->integer('address_id'));

            if (! $address) {
                $address = UserAddress::create([
                    'user_id' => auth()->id(),
                    'type' => 'shipping',
                    'full_name' => auth()->user()->name,
                    'phone' => auth()->user()->phone,
                    'address_line1' => $request->input('address_line_1'),
                    'city' => $request->input('city'),
                    'state' => $request->input('state'),
                    'postal_code' => $request->input('postal_code'),
                    'country' => $request->input('country'),
                    'is_default' => auth()->user()->addresses()->count() === 0,
                ]);
            }

            $total = max($subtotal - $discount, 0);

            $order = Order::create([
                'order_number' => 'ORD-'.strtoupper(Str::random(8)).'-'.now()->format('ymd'),
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'tax_amount' => 0,
                'shipping_amount' => 0,
                'total_amount' => $total,
                'coupon_code' => $coupon ? $coupon->code : null,
                'currency' => 'INR',
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => $paymentMethod,
                'shipping_address_id' => $address->id,
                'billing_address_id' => $address->id,
                'placed_at' => now(),
            ]);

            foreach ($items as $item) {
                $unitPrice = $item->unit_price;
                $lineTotal = $unitPrice * $item->quantity;
                $commissionRate = (float) ($item->product->vendor?->commission_rate ?? 0);
                $commissionAmount = round($lineTotal * ($commissionRate / 100), 2);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'vendor_id' => $item->product->vendor_id,
                    'product_name' => $item->product->name,
                    'sku' => $item->product->sku,
                    'quantity' => $item->quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                    'commission_rate' => $commissionRate,
                    'commission_amount' => $commissionAmount,
                    'vendor_earning' => $lineTotal - $commissionAmount,
                ]);

                $item->product->decrement('stock_quantity', $item->quantity);
            }

            Payment::create([
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'payment_method' => $paymentMethod,
                'amount' => $total,
                'currency' => 'INR',
                'status' => 'pending',
            ]);

            if ($coupon) {
                $coupon->increment('used_count');
                auth()->user()->coupons()->syncWithoutDetaching([$coupon->id]);
                DB::table('coupon_user')
                    ->where('coupon_id', $coupon->id)
                    ->where('user_id', auth()->id())
                    ->increment('usage_count');
            }

            session()->forget('coupon_code');
            $cart->items()->delete();
            $cart->delete();

            return $order;
        });

        return redirect()->route('orders')->with('success', "Order #{$order->order_number} placed successfully.");
    }
}
