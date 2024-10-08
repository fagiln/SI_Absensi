<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;

// use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(string $id){
        $user = User::find($id);

        // return response()->json($user);
        return view('user.profile', compact('user'));
    }

    public function update_foto(Request $request){
        
    }

    public function update_dataprofile(Request $request){

    // // Validasi input
    // $validatedData = $request->validate([
    //     'nama' => 'required|string|max:255',
    //     'NIK' => 'required|number|max:155',
    //     'tempat_tgl' => 'required|string|max:155',
    //     'email' => 'required|string|max:155',
    //     'no_hp' => 'required|number|max:15',
    // ]);

    // // Mengambil data yang sudah divalidasi
    // $nama = $validatedData['nama'];
    // $NIK = $validatedData['NIK'];
    // $tempat_tgl = $validatedData['tempat_tgl'];
    // $email = $validatedData['email'];
    // $no_hp = $validatedData['no_hp'];

    // // Menyimpan ke database atau operasi lainnya...
    // return response()->json([
    //     'nama' => $nama,
    //     'NIK' => $NIK,
    //     'tempat_tgl' => $tempat_tgl,
    //     'email' => $email,
    //     'no_hp' => $no_hp,
    // ]);
    
    // return redirect()->back()->with('success', 'Data berhasil disimpan.');

    }

    public function update_userpass(){

    }
}