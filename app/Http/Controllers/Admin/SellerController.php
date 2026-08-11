<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanTier;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SellerController extends Controller
{
    public function index()
    {
        $sellers = User::where('role', 'seller')
            ->with('vendor')
            ->latest()
            ->get();

        return view('admin.sellers', compact('sellers'));
    }

    public function edit(User $seller)
    {
        if ($seller->role !== 'seller') {
            abort(404);
        }

        $subscription = $seller->subscriptions()->latest('id')->first();
        $plans = Plan::where('status', 'active')
            ->where('type', 'subscription')
            ->orderBy('price')
            ->get();

        return view('admin.editSeller', compact('seller', 'subscription', 'plans'));
    }

    public function update(Request $request, User $seller)
    {
        if ($seller->role !== 'seller') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$seller->id,
            'shop_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'store_description' => 'nullable|string|max:5000',
            'store_address' => 'nullable|string|max:1000',
            'store_city' => 'nullable|string|max:100',
            'store_state' => 'nullable|string|max:100',
            'store_postal_code' => 'nullable|string|max:20',
            'store_country' => 'nullable|string|max:100',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'commission_type' => 'nullable|in:percentage,fixed',
            'tax_number' => 'nullable|string|max:50',
            'status' => 'nullable|in:approved,pending,suspended,rejected',
            'plan_id' => 'nullable|integer|exists:plans,id',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'store_banner' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:8192',
        ]);

        $seller->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ]);

        if ($seller->vendor) {
            $vendor = $seller->vendor;

            $vendor->update([
                'store_name' => $request->input('shop_name') ?: $vendor->store_name,
                'description' => $request->input('store_description'),
                'address' => $request->input('store_address'),
                'city' => $request->input('store_city'),
                'state' => $request->input('store_state'),
                'postal_code' => $request->input('store_postal_code'),
                'country' => $request->input('store_country'),
                'commission_rate' => $request->filled('commission_rate') ? $request->input('commission_rate') : $vendor->commission_rate,
                'commission_type' => $request->input('commission_type') ?: $vendor->commission_type,
                'tax_number' => $request->input('tax_number'),
            ]);

            if ($request->filled('status') && $request->input('status') !== $vendor->status) {
                $vendor->status = $request->input('status');
                if ($request->input('status') === 'approved' && ! $vendor->approved_at) {
                    $vendor->approved_at = now();
                }
            }
            $vendor->save();

            if ($request->filled('status') && $request->input('status') !== $seller->status) {
                $seller->status = $request->input('status');
                $seller->save();
            }

            if ($request->hasFile('store_logo')) {
                $filename = 'logo-'.time().'-'.Str::random(6).'.'.$request->file('store_logo')->getClientOriginalExtension();
                $request->file('store_logo')->move(public_path('images/logos'), $filename);
                $vendor->logo = $filename;
                $vendor->save();
            }

            if ($request->hasFile('store_banner')) {
                $filename = 'banner-'.time().'-'.Str::random(6).'.'.$request->file('store_banner')->getClientOriginalExtension();
                $request->file('store_banner')->move(public_path('images/banners'), $filename);
                $vendor->banner = $filename;
                $vendor->save();
            }
        }

        $this->updateSellerPlan($seller, $request->filled('plan_id') ? $request->integer('plan_id') : null);

        return redirect()->route('admin.seller.index')->with('success', 'Seller updated successfully.');
    }

    private function updateSellerPlan(User $seller, ?int $planId): void
    {
        if (! $planId) {
            return;
        }

        $plan = Plan::where('id', $planId)->where('status', 'active')->firstOrFail();

        $currentPlanId = $seller->subscriptions()
            ->whereIn('status', ['active', 'trialing'])
            ->latest('id')
            ->first()
            ?->planTier?->plan_id;

        if ($currentPlanId === $plan->id) {
            return;
        }

        $tier = $plan->tiers->where('is_active', true)->sortBy('sort_order')->first()
            ?? PlanTier::create([
                'plan_id' => $plan->id,
                'name' => $plan->name,
                'slug' => $plan->slug.'-default',
                'price' => $plan->price,
                'billing_period' => 'monthly',
                'trial_days' => 0,
                'sort_order' => 0,
                'is_active' => true,
            ]);

        $seller->subscriptions()
            ->whereIn('status', ['active', 'trialing', 'past_due'])
            ->update([
                'status' => 'cancelled',
                'ends_at' => now(),
                'cancel_at_period_end' => true,
            ]);

        $now = Carbon::now();

        Subscription::create([
            'user_id' => $seller->id,
            'plan_tier_id' => $tier->id,
            'status' => 'trialing',
            'current_period_start' => $now,
            'current_period_end' => $now->copy()->addDays(max(1, (int) $plan->duration_days)),
            'trial_ends_at' => null,
            'price' => $plan->price,
            'billing_period' => $tier->billing_period,
        ]);
    }

    public function approve(User $seller)
    {
        if ($seller->role !== 'seller') {
            abort(404);
        }

        $seller->update(['status' => 'approved']);

        if ($seller->vendor) {
            $seller->vendor->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);
        }

        return redirect()->route('admin.seller.index')->with('success', 'Seller approved.');
    }

    public function reject(User $seller)
    {
        if ($seller->role !== 'seller') {
            abort(404);
        }

        $seller->update(['status' => 'rejected']);

        if ($seller->vendor) {
            $seller->vendor->update(['status' => 'rejected']);
        }

        return redirect()->route('admin.seller.index')->with('success', 'Seller rejected.');
    }

    public function destroy(User $seller)
    {
        if ($seller->role !== 'seller') {
            abort(404);
        }

        $seller->delete();

        return redirect()->route('admin.seller.index')->with('success', 'Seller deleted.');
    }
}
