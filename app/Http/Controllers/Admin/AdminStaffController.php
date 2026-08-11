<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminStaffController extends Controller
{
    public function index()
    {
        $staff = Admin::orderBy('name')->get();

        return view('admin.adminStaff', compact('staff'));
    }

    public function create()
    {
        return view('admin.editAdminStaff', ['staff' => new Admin]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:super_admin,support,finance,content',
        ]);

        Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member created.');
    }

    public function edit(Admin $staff)
    {
        return view('admin.editAdminStaff', compact('staff'));
    }

    public function update(Request $request, Admin $staff)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,'.$staff->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:super_admin,support,finance,content',
        ]);

        $staff->name = $data['name'];
        $staff->email = $data['email'];
        $staff->role = $data['role'];

        if (! empty($data['password'])) {
            $staff->password = $data['password'];
        }

        $staff->save();

        return redirect()->route('admin.staff.index')->with('success', 'Staff member updated.');
    }

    public function destroy(Admin $staff)
    {
        if ($staff->role === 'super_admin') {
            return back()->with('error', 'Super admin cannot be deleted.');
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff member deleted.');
    }
}
