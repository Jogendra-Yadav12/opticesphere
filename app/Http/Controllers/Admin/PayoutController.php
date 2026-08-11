<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function index()
    {
        $payouts = PayoutRequest::with(['vendor.user', 'processedBy'])->latest()->get();

        return view('admin.payouts', compact('payouts'));
    }

    public function approve(PayoutRequest $payout)
    {
        $payout->status = 'approved';
        $payout->processed_by = auth()->id();
        $payout->save();

        return redirect()->route('admin.payout.index')->with('success', 'Payout approved.');
    }

    public function process(Request $request, PayoutRequest $payout)
    {
        $data = $request->validate(['gateway_transaction_id' => 'nullable|string|max:191']);

        $payout->status = 'completed';
        $payout->gateway_transaction_id = $data['gateway_transaction_id'] ?? null;
        $payout->processed_by = auth()->id();
        $payout->processed_at = now();
        $payout->save();

        return redirect()->route('admin.payout.index')->with('success', 'Payout marked as processed.');
    }

    public function reject(PayoutRequest $payout)
    {
        $payout->status = 'cancelled';
        $payout->processed_by = auth()->id();
        $payout->processed_at = now();
        $payout->save();

        return redirect()->route('admin.payout.index')->with('success', 'Payout cancelled.');
    }
}
