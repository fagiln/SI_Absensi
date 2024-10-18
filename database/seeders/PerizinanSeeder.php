<?php

namespace Database\Seeders;

use App\Models\Perizinan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Faker\Factory as Faker; // Pastikan Faker diimpor di sini

class PerizinanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     // Ambil semua pengguna untuk ditambahkan datanya
    //     $users = User::all();

    //     // Loop melalui setiap user dan buat beberapa data perizinan untuk masing-masing
    //     foreach ($users as $user) {
    //         Perizinan::create([
    //             'user_id' => $user->id,
    //             'start_date' => Carbon::now()->subDays(rand(1, 10))->toDateString(),
    //             'end_date' => Carbon::now()->toDateString(),
    //             'reason' => 'sakit',
    //             'keterangan' => 'Mengajukan izin karena sakit.',
    //             'bukti_path' => 'path/to/bukti.jpg', // Sesuaikan path ini dengan lokasi bukti yang disimpan
    //             'status' => 'pending',
    //             'created_at' => '2024-10-04',

    //         ]);

    //         Perizinan::create([
    //             'user_id' => $user->id,
    //             'start_date' => Carbon::now()->subDays(rand(11, 20))->toDateString(),
    //             'end_date' => Carbon::now()->subDays(rand(5, 10))->toDateString(),
    //             'reason' => 'izin',
    //             'keterangan' => 'Mengajukan izin karena urusan keluarga.',
    //             'bukti_path' => 'path/to/bukti.pdf', // Sesuaikan path ini dengan lokasi bukti yang disimpan
    //             'status' => 'diterima',
    //         ]);
    //     }
    // }

    public function run()
{ 
    // Mengambil user dengan id 2
    $user2 = User::find(2); // menggunakan find untuk mendapatkan user dengan id


        foreach ($users as $user) {
            Perizinan::create([
                'user_id' => $user->id,
                'start_date' => Carbon::now()->subDays(rand(1, 10))->toDateString(),
                'end_date' => Carbon::now()->toDateString(),
                'reason' => $reasons[array_rand($reasons)], // Ambil alasan secara acak dari array
                'keterangan' => 'Mengajukan izin karena ' . $this->generateRandomReason(), // Keterangan yang berbeda
                'bukti_path' => 'path/to/bukti_' . $user->id . '.jpg', // Sesuaikan path ini dengan lokasi bukti yang disimpan
                'status' => $statuses[array_rand($statuses)], // Ambil status secara acak dari array
                'created_at' => now(), // Tanggal sekarang
            ]);
        }
    }
    private function generateRandomReason()
    {
        $reasons = ['sakit', 'kegiatan keluarga', 'pernikahan', 'dinas luar', 'acara penting'];
        return $reasons[array_rand($reasons)];
    }

    // Fungsi untuk menghasilkan alasan acak
}
