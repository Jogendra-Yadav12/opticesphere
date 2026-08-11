<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $query = Order::with('user');

        if (auth()->user()->role !== 'admin') {
            $vendor = auth()->user()->vendor;

            $query->whereHas('items', fn ($q) => $q->where('vendor_id', $vendor?->id));
        }

        $orders = $query->latest()->get();

        return view('admin.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        if (auth()->user()->role !== 'admin') {
            $vendor = auth()->user()->vendor;

            $belongsToVendor = $order->items()->where('vendor_id', $vendor?->id)->exists();

            if (! $belongsToVendor) {
                abort(403);
            }
        }

        $order->load(['user', 'items', 'statusHistories', 'payments', 'refunds', 'returns', 'shipments', 'shippingAddress', 'billingAddress']);

        return view('admin.orderDetails', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'payment_status' => 'required|in:unpaid,paid,failed,refunded,partially_refunded',
        ]);

        $order->status = $data['status'];
        $order->payment_status = $data['payment_status'];
        $order->save();

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $data['status'],
            'comment' => 'Updated by '.auth()->user()->name,
            'causer_type' => get_class(auth()->user()),
            'causer_id' => auth()->id(),
        ]);

        return redirect()->route('admin.order.show', $order->id)->with('success', 'Order status updated.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.order.index')->with('success', 'Order deleted.');
    }
}
