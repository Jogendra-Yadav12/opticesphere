<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::latest()->get();

        return view('admin.plans', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'product_limit' => 'required|integer|min:0',
        ]);

        Plan::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'slug' => Str::slug($request->input('name')).'-'.Str::random(4),
            'type' => 'subscription',
            'status' => 'active',
            'price' => $request->input('price'),
            'duration_days' => $request->integer('duration_days'),
            'product_limit' => $request->integer('product_limit'),
            'purchase_enabled' => $request->boolean('purchase_enabled'),
        ]);

        return redirect()->route('admin.plan.index')->with('success', 'Plan created successfully.');
    }

    public function edit(Plan $plan)
    {
        return view('admin.editPlan', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'product_limit' => 'required|integer|min:0',
        ]);

        $plan->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'duration_days' => $request->integer('duration_days'),
            'product_limit' => $request->integer('product_limit'),
            'purchase_enabled' => $request->boolean('purchase_enabled'),
        ]);

        return redirect()->route('admin.plan.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()->route('admin.plan.index')->with('success', 'Plan deleted successfully.');
    }
}
