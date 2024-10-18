<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\KaryawanDataTable;
use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    public function index(KaryawanDataTable $dataTable)
    {
        $departemen = Department::all();
        $user = User::all();
        return $dataTable->render('admin.karyawan.karyawan', compact('departemen', 'user'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nik' => 'required|unique:users,nik|digits_between:6,20|numeric',
            'username' => 'required|unique:users,username|max:10',
            'department_id' => 'required',
            'password' => 'required|string|min:6',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
            'nik.digits_between' => 'NIK harus terdiri dari 6 hingga 20 angka.',
            'nik.numeric' => 'NIK harus berupa angka.',

            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan.',
            'username.max' => 'Username tidak boleh lebih dari 10 karakter.',

            'department_id.required' => 'Departemen harus dipilih.',

            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa string.',
            'password.min' => 'Password harus minimal 6 karakter.',
        ]);        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);

        if ($request->filled('edit_password')) {
            $data['password'] = Hash::make($request->edit_password);

        }
        User::create($data);

        return redirect()->back()->with('status', 'Berhasil Menambahkan Karyawan');
    }

    public function destroy(string $id)
    {
        $user = User::find(id: $id);
        $user->delete();
        return response()->json([
            'message' => 'Data karyawan berhasil dihapus!'
        ], 200);
    }
    public function edit($id)
    {
        $user = User::find($id); // Ambil data user dari database
        // $departemen = Department::all(); // Jika perlu departemen
        return response()->json($user); // Return data dalam format JSON
    }

    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        $request->validate([
            'edit_nik' => 'required|digits_between:6,20|numeric',
            'edit_jabatan' => 'required|string',
            'edit_password' => 'nullable|min:6',
        ], [
            'edit_nik.required' => 'NIK wajib diisi.',
            'edit_nik.digits_between' => 'NIK harus terdiri dari 6 hingga 20 angka.',
            'edit_nik.numeric' => 'NIK harus berupa angka.',

            'edit_jabatan.required' => 'Jabatan wajib diisi.',
            'edit_jabatan.string' => 'Jabatan harus berupa teks yang valid.',

            'edit_password.min' => 'Password harus minimal 6 karakter jika diisi.',
        ]);
        $data = [
            'nik' =>   $request->edit_nik,
            'jabatan' => $request->edit_jabatan,
        ];
        if ($request->filled('edit_password')) {
            $data['password'] = Hash::make($request->edit_password);
        }

        $user->update($data);
        return redirect()->back()->with('status', 'Karyawan Berhasil di Edit');
    }

}
