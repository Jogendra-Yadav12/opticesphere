<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();

        return view('profile-account', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.Auth::id(),
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');

        if ($request->filled('password')) {
            $user->password = $request->input('password');
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }

    public function orders()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10)->withQueryString();

        return view('orders', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items', 'statusHistories', 'shippingAddress', 'billingAddress']);

        return view('order-detail', compact('order'));
    }

    public function address()
    {
        $addresses = Auth::user()->addresses()->orderByDesc('is_default')->get();

        $editingAddress = null;
        if (request('edit')) {
            $editingAddress = $addresses->firstWhere('id', request('edit'));
        }

        return view('address', compact('addresses', 'editingAddress'));
    }

    public function addressStore(Request $request)
    {
        $data = $this->validateAddress($request);

        $data['address_line1'] = $data['address_line_1'];
        $data['address_line2'] = $data['address_line_2'] ?? null;
        unset($data['address_line_1'], $data['address_line_2']);

        UserAddress::create(array_merge($data, [
            'user_id' => Auth::id(),
            'type' => 'shipping',
            'is_default' => Auth::user()->addresses()->count() === 0,
        ]));

        return redirect()->route('address')->with('success', 'Address added successfully.');
    }

    public function addressUpdate(Request $request, UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $this->validateAddress($request);

        $data['address_line1'] = $data['address_line_1'];
        $data['address_line2'] = $data['address_line_2'] ?? null;
        unset($data['address_line_1'], $data['address_line_2']);

        $address->update($data);

        return redirect()->route('address')->with('success', 'Address updated successfully.');
    }

    public function addressMakeDefault(UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return redirect()->route('address')->with('success', 'Default address updated successfully.');
    }

    public function addressDestroy(UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->delete();

        return redirect()->route('address')->with('success', 'Address deleted successfully.');
    }

    private function validateAddress(Request $request): array
    {
        return $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
        ]);
    }
}
