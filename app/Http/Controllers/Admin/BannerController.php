<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->get();

        return view('admin.banners', compact('banners'));
    }

    public function create()
    {
        return view('admin.addBanner');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'link_url' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $filename = 'banner-'.time().'-'.Str::random(6).'.'.$request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(public_path('images/slider'), $filename);

        Banner::create([
            'title' => $request->input('title'),
            'subtitle' => $request->input('subtitle'),
            'image' => $filename,
            'link' => $request->input('link_url'),
            'position' => 'hero',
            'sort_order' => $request->input('sort_order', 0),
            'is_active' => $request->input('status') === 'active',
        ]);

        return redirect()->route('admin.banner.index')->with('success', 'Banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.editBanner', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'link_url' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $banner->title = $request->input('title');
        $banner->subtitle = $request->input('subtitle');
        $banner->link = $request->input('link_url');
        $banner->sort_order = $request->input('sort_order', 0);
        $banner->is_active = $request->input('status') === 'active';

        if ($request->hasFile('image')) {
            $filename = 'banner-'.time().'-'.Str::random(6).'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/slider'), $filename);
            $banner->image = $filename;
        }

        $banner->save();

        return redirect()->route('admin.banner.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('admin.banner.index')->with('success', 'Banner deleted successfully.');
    }
}
