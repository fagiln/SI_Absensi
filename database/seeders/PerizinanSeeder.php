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
            Perizinan::create([
                'user_id' => '2',
                'start_date' =>'2024-10-11',
                'end_date' => '2024-10-20',
                'reason' => 'izin',
                'keterangan' => 'Mengajukan izin karena sakit.',
                'bukti_path' => 'path/to/bukti.jpg', // Sesuaikan path ini dengan lokasi bukti yang disimpan
                // 'status' => 'pending',
                'created_at' => '2024-10-11',

            ]);

        
        }
    }

