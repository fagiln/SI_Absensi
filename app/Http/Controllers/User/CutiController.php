<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

// use Illuminate\Http\RedirectResponse;

class CutiController extends Controller
{
    public function show(){
       
        $pengajuanCuti = Perizinan::where('user_id', auth()->id())
                                    ->where('created_at', '>=', Carbon::now()->subMonth()) // Filter 1 bulan terakhir
                                    ->orderBy('created_at', 'desc') // Urutkan dari terbaru
                                    ->get();

        $pengajuanHariIni = Perizinan::where('user_id', auth()->id())
                                    ->whereDate('created_at', Carbon::today())
                                    ->get(); // Mengambil satu data terbaru jika ada
                            
        $AjukanUlang = false; // Default, boleh ajukan ulang
                            
        foreach ($pengajuanHariIni as $pengajuan) {
            if ($pengajuan->status != 'ditolak') {
                // Jika ada yang tidak ditolak, user tidak boleh ajukan ulang
                $AjukanUlang = true;
                break; // Keluar dari loop, tidak perlu cek lebih lanjut
            }
        }
                            
        return view('user.cuti.cuti', compact('pengajuanCuti', 'AjukanUlang', 'pengajuanHariIni'));
    }

    public function create_cuti(Request $request){
    
        $validator = Validator::make($request->all(), [
            'filter_izin' => 'required|in:sakit,izin',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'alasan' => 'required|string|max:255',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'filter_izin.required' => 'Pilih jenis cuti wajib diisi.',
            'filter_izin.in' => 'Jenis cuti yang dipilih tidak valid.',
            'start_date.required' => 'Tanggal awal cuti wajib diisi.',
            'start_date.date' => 'Tanggal awal cuti harus dalam format yang benar.',
            'start_date.after_or_equal' => 'Tanggal awal cuti tidak boleh sebelum hari ini.',
            'end_date.required' => 'Tanggal akhir cuti wajib diisi.',
            'end_date.date' => 'Tanggal akhir cuti harus dalam format yang benar.',
            'end_date.after_or_equal' => 'Tanggal akhir cuti harus setelah atau sama dengan tanggal awal.',
            'alasan.required' => 'Alasan cuti wajib diisi.',
            'alasan.max' => 'Alasan cuti tidak boleh lebih dari 255 karakter.',
            'file.required' => 'File wajib di unggah',
            'file.mimes' => 'File yang diunggah harus dalam format jpg, jpeg, png, atau pdf.',
            'file.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
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
            // Tentukan path penyimpanan di public/storage/perizinan
        $destinationPath = public_path('storage/perizinan');
        $createdAt =  now()->format('YmdHi');
        $createdAt =  now()->format('YmdHi');
        $filename = $userId . '_' . $createdAt . '.' . $file->getClientOriginalExtension();
        // Nama file dengan user ID dan timestamp

        // Pindahkan file ke direktori public/storage/perizinan
        $file->move($destinationPath, $filename);

        // Simpan hanya nama file ke database, tanpa path 'uploads'
        $cuti->bukti_path = $filename;// Menyimpan path file ke database
        }

        $cuti->save(); // Simpan data ke database

        // Redirect atau kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Pengajuan cuti berhasil diajukan.');
    }
    
    public function showdetail($id){
        
        $cuti = Perizinan::findOrFail($id);
        
        return view('user.cuti.cuti-detail', compact('cuti'));

    }
}