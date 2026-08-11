<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $payments = PaymentMethod::latest()->get();

        return view('admin.payments', compact('payments'));
    }

    public function create()
    {
        return view('admin.createPayment');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_methods,code',
            'description' => 'nullable|string',
            'is_active' => 'required|in:0,1',
        ]);

        PaymentMethod::create([
            'name' => $request->input('name'),
            'code' => strtolower($request->input('code')),
            'description' => $request->input('description'),
            'credentials' => $this->settingsFromRequest($request),
            'is_active' => (bool) $request->integer('is_active'),
        ]);

        return redirect()->route('admin.payment.index')->with('success', 'Payment method created successfully.');
    }

    public function edit(PaymentMethod $payment)
    {
        return view('admin.editPayment', compact('payment'));
    }

    public function update(Request $request, PaymentMethod $payment)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_methods,code,'.$payment->id,
            'description' => 'nullable|string',
            'is_active' => 'required|in:0,1',
        ]);

        $payment->update([
            'name' => $request->input('name'),
            'code' => strtolower($request->input('code')),
            'description' => $request->input('description'),
            'credentials' => $this->settingsFromRequest($request),
            'is_active' => (bool) $request->integer('is_active'),
        ]);

        return redirect()->route('admin.payment.index')->with('success', 'Payment method updated successfully.');
    }

    public function destroy(PaymentMethod $payment)
    {
        $payment->delete();

        return redirect()->route('admin.payment.index')->with('success', 'Payment method deleted successfully.');
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
