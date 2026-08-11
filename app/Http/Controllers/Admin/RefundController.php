<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function index()
    {
        $refunds = Refund::with(['order', 'orderItem', 'processedBy'])->latest()->get();

        return view('admin.refunds', compact('refunds'));
    }

    public function approve(Refund $refund)
    {
        $refund->status = 'approved';
        $refund->processed_by = auth()->id();
        $refund->processed_at = now();
        $refund->save();

        return redirect()->route('admin.refund.index')->with('success', 'Refund approved.');
    }

    public function reject(Refund $refund)
    {
        $refund->status = 'rejected';
        $refund->processed_by = auth()->id();
        $refund->processed_at = now();
        $refund->save();

        return redirect()->route('admin.refund.index')->with('success', 'Refund rejected.');
    }
}
