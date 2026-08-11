<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->with('orders')
            ->latest()
            ->get();

        return view('admin.customers', compact('customers'));
    }

    public function edit(User $customer)
    {
        $customer->loadCount('orders');

        return view('admin.editCustomer', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$customer->id,
            'phone' => 'nullable|string|max:30',
            'status' => 'required|in:active,banned',
        ]);

        $customer->name = $data['name'];
        $customer->email = $data['email'];
        $customer->phone = $data['phone'] ?? null;
        $customer->status = $data['status'];

        $customer->save();

        return redirect()->route('admin.customer.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(User $customer)
    {
        $customer->delete();

        return redirect()->route('admin.customer.index')->with('success', 'Customer deleted successfully.');
    }
}
