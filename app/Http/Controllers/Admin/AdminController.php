<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function edit($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }
    public function update(Request $request, string $id)
    {
        // $userId = Auth::user()->id;
        $user = User::find($id);
        $request->validate([

            'admin_password' => 'nullable|min:6',
            'admin_no' => 'nullable',
        ], [

            'admin_password.min' => 'Password harus minimal 6 karakter jika diisi.',
        ]);
        $data = [];
        if ($request->filled('admin_password')) {
            $data['password'] = Hash::make($request->admin_password);
        } elseif ($request->filled('admin_no')) {
            $data['no_hp'] = $request->admin_no;
        }

        $user->update($data);
        return redirect()->back()->with('status', 'Admin Berhasil di Edit');
    }
}
