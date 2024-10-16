<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PerizinanDataTable;
use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use Illuminate\Http\Request;

class PerizinanController extends Controller
{
    public function index(PerizinanDataTable $dataTable)
    {
        $perizinan = Perizinan::all();

        return $dataTable->render('admin.perizinan.perizinan', compact('perizinan'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak,pending',
            'reason' => 'nullable|string'
        ]);
        $data = [
            'status' => $request->status,

        ];

        if ($request->status == 'ditolak') {
            $data['sattus'] = $request->status;
            $data['keterangan_ditolak'] = $request->reason;
        } else {
            $data['keterangan_ditolak'] = null;
        }


        $perizinan = Perizinan::findOrFail($id);
        $perizinan->update($data);
        return redirect()->back()->with('status', 'Berhasil Mengedit status');
    }

    public function edit(string $id)
    {
        $perizinan = Perizinan::find($id);
        return response()->json($perizinan);
    }
}
