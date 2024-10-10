<?php

namespace Database\Seeders;

use App\Models\Perizinan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PerizinanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua pengguna untuk ditambahkan datanya
        $users = User::all();

        // Loop melalui setiap user dan buat beberapa data perizinan untuk masing-masing
        foreach ($users as $user) {
            Perizinan::create([
                'user_id' => $user->id,
                'start_date' => Carbon::now()->toDateString(),
                'end_date' => Carbon::now()->toDateString(),
                'reason' => 'sakit',
                'keterangan' => 'Mengajukan izin karena sakit.',
                'bukti_path' => 'path/to/bukti.jpg', // Sesuaikan path ini dengan lokasi bukti yang disimpan
                'status' => 'pending',
                'created_at' => '2024-10-04',

            ]);

            Perizinan::create([
                'user_id' => $user->id,
                'start_date' => Carbon::now()->toDateString(),
                'end_date' => Carbon::now()->subDays(rand(5, 10))->toDateString(),
                'reason' => 'izin',
                'keterangan' => 'Mengajukan izin karena urusan keluarga.',
                'bukti_path' => 'path/to/bukti.pdf', // Sesuaikan path ini dengan lokasi bukti yang disimpan
                'status' => 'diterima',
            ]);
        }
    }
}
