<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CurrencyRate;
use Illuminate\Http\Request;

class CurrencyRateController extends Controller
{
    public function index()
    {
        $rates = CurrencyRate::orderBy('base_currency')->orderBy('target_currency')->get();

        return view('admin.currencyRates', compact('rates'));
    }

    public function create()
    {
        return view('admin.editCurrencyRate', ['rate' => new CurrencyRate]);
    }

    public function store(Request $request)
    {
        $data = $this->validateRate($request);

        CurrencyRate::create([
            'base_currency' => strtoupper($data['base_currency']),
            'target_currency' => strtoupper($data['target_currency']),
            'rate' => $data['rate'],
        ]);

        return redirect()->route('admin.currency.index')->with('success', 'Currency rate created.');
    }

    public function edit(CurrencyRate $currencyRate)
    {
        return view('admin.editCurrencyRate', compact('currencyRate'));
    }

    public function update(Request $request, CurrencyRate $currencyRate)
    {
        $data = $this->validateRate($request);

        $currencyRate->base_currency = strtoupper($data['base_currency']);
        $currencyRate->target_currency = strtoupper($data['target_currency']);
        $currencyRate->rate = $data['rate'];
        $currencyRate->save();

        return redirect()->route('admin.currency.index')->with('success', 'Currency rate updated.');
    }

    public function destroy(CurrencyRate $currencyRate)
    {
        $currencyRate->delete();

        return redirect()->route('admin.currency.index')->with('success', 'Currency rate deleted.');
    }

    protected function validateRate(Request $request): array
    {
        return $request->validate([
            'base_currency' => 'required|string|size:3',
            'target_currency' => 'required|string|size:3|different:base_currency',
            'rate' => 'required|numeric|gt:0',
        ]);
    }
}
