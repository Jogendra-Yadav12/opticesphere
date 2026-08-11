<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\VendorSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public const DAY_NAMES = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    public function index()
    {
        $seller = auth()->user()->load('vendor');

        $storeHours = [];
        $dayNames = self::DAY_NAMES;

        if ($seller->vendor) {
            $storeHours = $seller->vendor->store_hours;
        }

        return view('seller.settings', compact('seller', 'storeHours', 'dayNames'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255|unique:users,email,'.auth()->id(),
            'phone' => 'nullable|string|max:20',
            'shop_name' => 'nullable|string|max:255',
            'store_address' => 'nullable|string|max:1000',
            'store_description' => 'nullable|string|max:5000',
            'store_city' => 'nullable|string|max:100',
            'store_state' => 'nullable|string|max:100',
            'store_postal_code' => 'nullable|string|max:20',
            'store_country' => 'nullable|string|max:100',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'store_banner' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:8192',
            'hours' => 'nullable|array',
            'hours.*.open' => 'nullable|date_format:H:i',
            'hours.*.close' => 'nullable|date_format:H:i',
        ]);

        $user = auth()->user();
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->save();

        $vendor = $user->vendor;

        if ($vendor) {
            $vendor->store_name = $request->input('shop_name') ?: $vendor->store_name;
            $vendor->address = $request->input('store_address');
            $vendor->description = $request->input('store_description');
            $vendor->city = $request->input('store_city');
            $vendor->state = $request->input('store_state');
            $vendor->postal_code = $request->input('store_postal_code');
            $vendor->country = $request->input('store_country');
            $vendor->save();

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

            $hours = [];
            foreach (array_keys($request->input('hours', [])) as $day) {
                $hours[$day] = [
                    'is_closed' => $request->boolean("hours.$day.is_closed"),
                    'open' => $request->input("hours.$day.open"),
                    'close' => $request->input("hours.$day.close"),
                ];
            }

            VendorSetting::updateOrCreate(
                ['vendor_id' => $vendor->id, 'key' => 'store_hours'],
                ['value' => json_encode($hours)]
            );
        }

        return redirect()->route('seller.settings')->with('success', 'Settings updated successfully.');
    }
}
