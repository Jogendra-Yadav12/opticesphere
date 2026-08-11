<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShippingMethodController extends Controller
{
    public function index()
    {
        $shipping = ShippingMethod::latest()->get();

        return view('admin.shipping', compact('shipping'));
    }

    public function create()
    {
        return view('admin.createShipping');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:shipping_methods,code',
            'base_cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'required|in:0,1',
        ]);

        ShippingMethod::create([
            'name' => $request->input('name'),
            'code' => strtolower($request->input('code')),
            'slug' => Str::slug($request->input('name')).'-'.Str::random(4),
            'base_cost' => $request->input('base_cost'),
            'cost_per_kg' => 0,
            'estimated_days_min' => 3,
            'estimated_days_max' => 7,
            'is_active' => (bool) $request->integer('is_active'),
            'settings' => $this->settingsFromRequest($request),
        ]);

        return redirect()->route('admin.shipping.index')->with('success', 'Shipping method created successfully.');
    }

    public function edit(ShippingMethod $shipping)
    {
        return view('admin.editShipping', compact('shipping'));
    }

    public function update(Request $request, ShippingMethod $shipping)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:shipping_methods,code,'.$shipping->id,
            'base_cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'required|in:0,1',
        ]);

        $shipping->update([
            'name' => $request->input('name'),
            'code' => strtolower($request->input('code')),
            'base_cost' => $request->input('base_cost'),
            'description' => $request->input('description'),
            'is_active' => (bool) $request->integer('is_active'),
            'settings' => $this->settingsFromRequest($request),
        ]);

        return redirect()->route('admin.shipping.index')->with('success', 'Shipping method updated successfully.');
    }

    public function destroy(ShippingMethod $shipping)
    {
        $shipping->delete();

        return redirect()->route('admin.shipping.index')->with('success', 'Shipping method deleted successfully.');
    }

    private function settingsFromRequest(Request $request): array
    {
        $keys = $request->input('setting_keys', []);
        $values = $request->input('setting_values', []);
        $settings = [];

        foreach ($keys as $index => $key) {
            if ($key !== null && $key !== '') {
                $settings[$key] = $values[$index] ?? '';
            }
        }

        return $settings;
    }
}
