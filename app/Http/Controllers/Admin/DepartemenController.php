<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\DepartemenDataTable;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartemenController extends Controller
{
    public function index(DepartemenDataTable $dataTable)
    {

        return $dataTable->render('admin.departemen.departemen');
    }

    public function store(Request $request)
    {
        $request->validate([
            'add_nama' => 'required|max:50',
            'add_kode' => 'required|unique:department,kode_departemen|max:10'

        ], [
            'add_nama.required' => 'Nama Departemen tidak boleh kosong',
            'add_kode.required' => 'Kode Departemen tidak boleh kosong',
            'add_kode.unique' => 'Kode sudah terpakai',
            'add_kode.max' => 'Kode maksimal 10 huruf',
            'add_nama.max' => 'Nama maksimal 10 huruf',

        ]);
        $data = [
            'nama_departemen' => $request->add_nama,
            'kode_departemen' => $request->add_kode,

        ];
        Department::create($data);
        return redirect()->back()->with('status', 'Data berhasil di tambah');
    }

    public function edit(string $id)
    {
        $departemen = Department::find($id);
        return response()->json($departemen);
    }
    public function update(Request $request, string $id)
    {
        $departemen = Department::find($id);
        $request->validate([
            'edit_nama' => 'required|max:50',
            'edit_kode' => 'required|max:10'

        ], [
            'edit_nama.required' => 'Nama Departemen tidak boleh kosong',
            'edit_kode.required' => 'Kode Departemen tidak boleh kosong',
            'edit_kode.max' => 'Kode maksimal 10 huruf',
            'edit_nama.max' => 'Nama maksimal 10 huruf',

        ]);
        $data = [
            'nama_departemen' => $request->edit_nama,
            'kode_departemen' => $request->edit_kode,
        ];
        $departemen->update($data);
        return redirect()->back()->with('status', 'Departemen berhasil di Edit');
    }

    public function destroy(string $id)
    {
        $departemen = Department::find($id);
        $departemen->delete();
        return response()->json([
            'message' => 'Data karyawan berhasil dihapus!'
        ], 200);
    }
}
