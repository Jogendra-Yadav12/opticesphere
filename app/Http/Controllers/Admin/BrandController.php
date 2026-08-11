<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('products')->orderBy('name')->get();

        return view('admin.brands', compact('brands'));
    }

    public function create()
    {
        return view('admin.editBrand', ['brand' => new Brand]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:2048',
            'is_active' => 'nullable|in:1,0',
        ]);

        $brand = Brand::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']).'-'.Str::random(4),
            'is_active' => ($data['is_active'] ?? 1) ? true : false,
        ]);

        if ($request->hasFile('logo')) {
            $filename = 'brand-'.time().'-'.Str::random(6).'.'.$request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move(public_path('images'), $filename);
            $brand->logo = $filename;
            $brand->save();
        }

        return redirect()->route('admin.brand.index')->with('success', 'Brand created.');
    }

    public function edit(Brand $brand)
    {
        return view('admin.editBrand', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:2048',
            'is_active' => 'nullable|in:1,0',
        ]);

        $brand->name = $data['name'];
        $brand->is_active = ($data['is_active'] ?? 1) ? true : false;

        if ($request->hasFile('logo')) {
            $filename = 'brand-'.time().'-'.Str::random(6).'.'.$request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move(public_path('images'), $filename);
            $brand->logo = $filename;
        }

        $brand->save();

        return redirect()->route('admin.brand.index')->with('success', 'Brand updated.');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();

        return redirect()->route('admin.brand.index')->with('success', 'Brand deleted.');
    }
}
