<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingZoneController extends Controller
{
    public function index()
    {
        $zones = ShippingZone::withCount('methods')->orderBy('name')->get();

        return view('admin.shippingZones', compact('zones'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get(['id', 'name', 'code']);

        return view('admin.editShippingZone', ['zone' => new ShippingZone, 'countries' => $countries]);
    }

    public function store(Request $request)
    {
        $data = $this->validateZone($request);

        ShippingZone::create([
            'name' => $data['name'],
            'countries' => $data['countries'] ?? [],
        ]);

        return redirect()->route('admin.shippingZone.index')->with('success', 'Shipping zone created.');
    }

    public function edit(ShippingZone $shippingZone)
    {
        $countries = Country::orderBy('name')->get(['id', 'name', 'code']);

        return view('admin.editShippingZone', ['zone' => $shippingZone, 'countries' => $countries]);
    }

    public function update(Request $request, ShippingZone $shippingZone)
    {
        $data = $this->validateZone($request);

        $shippingZone->name = $data['name'];
        $shippingZone->countries = $data['countries'] ?? [];
        $shippingZone->save();

        return redirect()->route('admin.shippingZone.index')->with('success', 'Shipping zone updated.');
    }

    public function destroy(ShippingZone $shippingZone)
    {
        $shippingZone->delete();

        return redirect()->route('admin.shippingZone.index')->with('success', 'Shipping zone deleted.');
    }

    protected function validateZone(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'countries' => 'nullable|array',
            'countries.*' => 'string|max:2',
        ]);
    }
}
