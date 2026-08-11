<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanTier;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $subscription = auth()->user()->subscriptions()->latest('id')->first();

        $plans = Plan::where('status', 'active')
            ->where('type', 'subscription')
            ->orderBy('price')
            ->get();

        return view('seller.plan', compact('subscription', 'plans'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|integer|exists:plans,id',
        ]);

        $plan = Plan::where('id', $validated['plan_id'])->where('status', 'active')->firstOrFail();

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

        $user = auth()->user();

        $user->subscriptions()
            ->whereIn('status', ['active', 'trialing', 'past_due'])
            ->update([
                'status' => 'cancelled',
                'ends_at' => now(),
                'cancel_at_period_end' => true,
            ]);

        $now = Carbon::now();

        Subscription::create([
            'user_id' => $user->id,
            'plan_tier_id' => $tier->id,
            'status' => 'trialing',
            'current_period_start' => $now,
            'current_period_end' => $now->copy()->addDays(max(1, $plan->duration_days)),
            'trial_ends_at' => null,
            'price' => $plan->price,
            'billing_period' => $tier->billing_period,
        ]);

        return redirect()->route('seller.plan.index')->with('success', 'Your plan has been updated to '.$plan->name.'.');
    }
}
