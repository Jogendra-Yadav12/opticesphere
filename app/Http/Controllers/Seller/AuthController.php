<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanTier;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('seller.auth.login');
    }

    public function showRegister()
    {
        $draft = session('seller_register_draft', []);

        return view('seller.auth.register', compact('draft'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:users,email',
            'store_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $request->session()->put('seller_register_draft', $data);

        return redirect()->route('seller.register.plan');
    }

    public function showPlan()
    {
        $draft = session('seller_register_draft');

        if (! $draft) {
            return redirect()->route('seller.register');
        }

        $plans = Plan::where('status', 'active')
            ->where('type', 'subscription')
            ->with('tiers')
            ->orderBy('price')
            ->get();

        return view('seller.auth.plan', compact('draft', 'plans'));
    }

    public function selectPlan(Request $request)
    {
        $draft = session('seller_register_draft');

        if (! $draft) {
            return redirect()->route('seller.register');
        }

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

        $user = User::create([
            'name' => $draft['name'],
            'phone' => $draft['phone'] ?? null,
            'email' => $draft['email'],
            'password' => $draft['password'],
            'role' => 'seller',
            'status' => 'pending',
        ]);

        $slug = Str::slug($draft['store_name']);
        $baseSlug = $slug;
        $suffix = 2;
        while (Vendor::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$suffix++;
        }

        Vendor::create([
            'user_id' => $user->id,
            'store_name' => $draft['store_name'],
            'slug' => $slug,
            'description' => $draft['description'] ?? null,
            'address' => $draft['address'] ?? null,
            'city' => $draft['city'] ?? null,
            'state' => $draft['state'] ?? null,
            'postal_code' => $draft['postal_code'] ?? null,
            'country' => $draft['country'] ?? null,
            'status' => 'pending',
            'commission_rate' => 0,
            'commission_type' => 'percentage',
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

        $request->session()->forget('seller_register_draft');

        return redirect()->route('seller.login')->with('success', 'Your seller account has been submitted. Our team will review and approve your store soon.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->withInput();
        }

        $user = Auth::user();

        if ($user->role !== 'seller') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['email' => 'Please use the customer or admin portal to sign in.'])->withInput();
        }

        if (in_array($user->status, ['pending', 'rejected', 'banned', 'suspended'], true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = $user->status === 'pending'
                ? 'Your seller account is awaiting admin approval. You will be able to log in once your store is approved.'
                : 'Your seller account is not approved. Please contact support.';

            return back()->withErrors(['email' => $message])->withInput();
        }

        $request->session()->regenerate();

        return redirect()->intended(route('seller.dashboard'));
    }
}
