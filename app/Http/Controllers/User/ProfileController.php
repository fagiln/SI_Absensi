<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;
// use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    
    public function show() {
        // Ambil user yang sedang login
        $user = Auth::user()->load('departemen');
    
        return view('user.profile', compact('user'));
    }
        
    public function update_foto(Request $request){
        
        // Validasi file
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Temukan user berdasarkan ID
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Cek dan hapus foto lama jika ada
        if ($user->avatar) {
            // Cek apakah foto ada di storage
            if (Storage::exists('public/photos/' . $user->avatar)) {
                // Hapus file lama
                Storage::delete('public/photos/' . $user->avatar);
            }
        }

        // Simpan foto baru
        if ($request->hasFile('photo')) {
            // Menghasilkan nama file unik
            $imageName = $user->id . $user->username . '.' . $request->photo->extension();

            // Simpan file baru di storage
            $request->photo->storeAs('public/photos', $imageName);

            // Update avatar di database dengan nama file baru
            $user->avatar = $imageName;
            $user->save(); // Simpan perubahan ke database

            return response()->json(['success' => true, 'avatar' => asset('storage/photos/' . $imageName)]);
        }

        return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
    }

    public function update_dataprofile(Request $request){

    // Temukan user yang ingin diupdate
    $user = Auth::user(); // Ganti find() dengan findOrFail() untuk menangani kasus tidak ditemukan

    // Validasi data (hanya untuk field yang diperlukan)
    $request->validate([
        'name' => [
            'nullable',
            'string',
            'min:2', // Minimal 2 karakter
            'max:40', // Maksimal 40 karakter
            'regex:/^[\p{L} ]+$/u' // Nama hanya boleh mengandung huruf dan spasi
        ],
        'username' => [
            'nullable',
            'string',
            'min:4', // Minimal 4 karakter
            'max:20', // Maksimal 20 karakter
            'alpha_num', // Hanya boleh huruf dan angka
            'unique:users,username,' // Pastikan untuk mengabaikan username yang sama saat update
        ],
        'email' => [
            'nullable',
            'email', // Format email yang valid
            'max:255', // Maksimal 255 karakter
            'unique:users,email,'// Pastikan untuk mengabaikan email yang sama saat update
        ],
        'no_hp' => [
            'nullable',
            'numeric', // Harus berupa angka
            'digits_between:10,15' // Minimal 10 dan maksimal 15 digit
        ],
    ], [
        'name.min' => 'Nama harus terdiri dari minimal 2 karakter.',
        'name.max' => 'Nama tidak boleh lebih dari 40 karakter.',
        'name.regex' => 'Nama hanya boleh mengandung huruf dan spasi.',
        'username.min' => 'Username harus terdiri dari minimal 4 karakter.',
        'username.max' => 'Username tidak boleh lebih dari 20 karakter.',
        'email.email' => 'Format email tidak valid.',
        'no_hp.numeric' => 'Nomor HP harus berupa angka.',
        'no_hp.digits_between' => 'Nomor HP harus terdiri dari minimal 10 dan maksimal 15 digit.'
    ]);

    // Update hanya jika data diisi
    if ($request->filled('name')) {
        $user->name = $request->name;
    }
    if ($request->filled('username')) {
        $user->username = $request->username;
    }
    if ($request->filled('email')) {
        $user->email = $request->email;
    }
    if ($request->filled('no_hp')) {
        $user->no_hp = $request->no_hp;
    }

    // Simpan perubahan ke database
    $user->save();

    // return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
    return redirect()->back()->with('success', 'Profile updated successfully');

    }

    public function update_pass(Request $request)
    {
        // Validasi input
        $request->validate([
            'new_password' => 'required|min:6|confirmed',
        ], [
            'new_password.required' => 'Password baru harus diisi.',
            'new_password.min' => 'Password minimal harus 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Mengambil user berdasarkan ID
        $user = Auth::user();
        
        // Memeriksa apakah user ditemukan
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Mengupdate password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diperbarui.');
    }

}
