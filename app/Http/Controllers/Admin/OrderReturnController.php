<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderReturn;
use Illuminate\Http\Request;

class OrderReturnController extends Controller
{
    public function index()
    {
        $returns = OrderReturn::with(['order', 'orderItem'])->latest()->get();

        return view('admin.returns', compact('returns'));
    }

    public function approve(OrderReturn $orderReturn)
    {
        $orderReturn->status = 'approved';
        $orderReturn->save();

        return redirect()->route('admin.return.index')->with('success', 'Return approved.');
    }

    public function reject(OrderReturn $orderReturn)
    {
        $orderReturn->status = 'rejected';
        $orderReturn->save();

        return redirect()->route('admin.return.index')->with('success', 'Return rejected.');
    }
}
