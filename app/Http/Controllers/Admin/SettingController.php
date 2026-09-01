<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();

        return view('admin.settings', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'setting_keys' => 'required|array',
            'setting_values' => 'required|array',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'default_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'contact_page_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
        ]);

        $keys = $request->input('setting_keys', []);
        $values = $request->input('setting_values', []);

        foreach ($keys as $index => $key) {
            if ($key === null || $key === '') {
                continue;
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $values[$index] ?? '', 'group' => 'general', 'type' => 'text']
            );
        }

        if ($request->hasFile('store_logo')) {
            $filename = 'logo-'.time().'-'.Str::random(6).'.'.$request->file('store_logo')->getClientOriginalExtension();
            $request->file('store_logo')->storeAs('images/logos', $filename, config('filesystems.default'));

            Setting::updateOrCreate(
                ['key' => 'store_logo'],
                ['value' => $filename, 'group' => 'general', 'type' => 'image']
            );
        }

        if ($request->hasFile('default_image')) {
            $filename = 'default-'.time().'-'.Str::random(6).'.'.$request->file('default_image')->getClientOriginalExtension();
            $request->file('default_image')->storeAs('images', $filename, config('filesystems.default'));

            Setting::updateOrCreate(
                ['key' => 'default_image'],
                ['value' => $filename, 'group' => 'general', 'type' => 'image']
            );
        }

        if ($request->hasFile('contact_page_image')) {
            $filename = 'contact-'.time().'-'.Str::random(6).'.'.$request->file('contact_page_image')->getClientOriginalExtension();
            $request->file('contact_page_image')->storeAs('images', $filename, config('filesystems.default'));

            Setting::updateOrCreate(
                ['key' => 'contact_page_image'],
                ['value' => $filename, 'group' => 'general', 'type' => 'image']
            );
        }

        return redirect()->route('admin.setting.index')->with('success', 'Settings saved successfully.');
    }
}
