<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\KaryawanDataTable;
use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Models\Departement;
use App\Models\User;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index(KaryawanDataTable $dataTable)
    {
        $departemen = Departement::all();
        return $dataTable->render('admin.karyawan.karyawan', compact('departemen'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nik' => 'required|unique:users,nik|digits:20|numeric',
            'username' => 'required|unique:users,username|max:10',
            'departement_id' => 'required',
            'password' => 'required|string|min:6',
        ]);

        User::create($data);
        return redirect()->back()->with('status', 'Berhasil Menambahkan Karyawan');
    }
}
