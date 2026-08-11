<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxRate;
use Illuminate\Http\Request;

class TaxRateController extends Controller
{
    public function index()
    {
        $taxRates = TaxRate::orderBy('name')->get();

        return view('admin.taxRates', compact('taxRates'));
    }

    public function create()
    {
        return view('admin.editTaxRate', ['taxRate' => new TaxRate]);
    }

    public function store(Request $request)
    {
        $data = $this->validateTax($request);

        TaxRate::create([
            'name' => $data['name'],
            'country' => $data['country'] ?? null,
            'state' => $data['state'] ?? null,
            'rate' => $data['rate'],
            'is_active' => ($data['is_active'] ?? 1) ? true : false,
        ]);

        return redirect()->route('admin.tax.index')->with('success', 'Tax rate created.');
    }

    public function edit(TaxRate $tax)
    {
        return view('admin.editTaxRate', compact('tax'));
    }

    public function update(Request $request, TaxRate $tax)
    {
        $data = $this->validateTax($request);

        $tax->name = $data['name'];
        $tax->country = $data['country'] ?? null;
        $tax->state = $data['state'] ?? null;
        $tax->rate = $data['rate'];
        $tax->is_active = ($data['is_active'] ?? 1) ? true : false;
        $tax->save();

        return redirect()->route('admin.tax.index')->with('success', 'Tax rate updated.');
    }

    public function destroy(TaxRate $tax)
    {
        $tax->delete();

        return redirect()->route('admin.tax.index')->with('success', 'Tax rate deleted.');
    }

    protected function validateTax(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:2',
            'state' => 'nullable|string|max:191',
            'rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'nullable|in:1,0',
        ]);
    }
}
