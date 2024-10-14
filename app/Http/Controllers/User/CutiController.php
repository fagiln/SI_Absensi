<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\perizinan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Http\RedirectResponse;

class CutiController extends Controller
{
    public function show(){
        return view('user.cuti');
    }

    public function create_cuti(Request $request){
    
        $validator = Validator::make($request->all(), [
            'filter_izin' => 'required|in:sakit,izin',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date',
            'alasan' => 'required|string|max:255', // Pastikan Anda menyesuaikan ini dengan field textarea
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Validasi file jika ada
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // dd(Auth::id());
        $userId = Auth::id();

        $cuti = new Perizinan();
        $cuti->user_id = $userId;
        $cuti->reason = $request->filter_izin;
        $cuti->start_date = $request->start_date;
        $cuti->end_date = $request->end_date;
        $cuti->keterangan = $request->alasan;

        // Jika ada file yang diupload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename); // Pastikan direktori ini ada dan dapat ditulisi
            $cuti->bukti_path = 'uploads/' . $filename; // Menyimpan path file ke database
        }

        $cuti->save(); // Simpan data ke database

        // Redirect atau kembali dengan pesan sukses
        return redirect()->back() // Ganti dengan route yang sesuai
            ->with('success', 'Pengajuan cuti berhasil diajukan.');
    }
    
    
    public function showdetail(){
        
        // $izin = Auth::all();

        return view('user.cuti-detail');
    }
}