<?php

namespace Database\Seeders;

use App\Models\Kehadiran;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class KehadiranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Misalnya, kita memiliki 10 user yang ingin kita isi dengan data kehadiran
        $users = User::all();

        foreach ($users as $user) {
            // Buat data kehadiran untuk setiap user
            Kehadiran::create([
                'user_id' => $user->id,
                'work_date' => Carbon::now()->subDays(rand(1, 30))->toDateString(),
                'check_in_time' => Carbon::now()->subHours(rand(1, 3))->toDateTimeString(),
                'check_out_time' => Carbon::now()->toDateTimeString(),
                'check_in_photo' => 'checkin_photo_' . $user->id . '.jpg',
                'check_out_photo' => 'checkout_photo_' . $user->id . '.jpg',
                'check_in_latitude' => '-6.200000',
                'check_in_longitude' => '106.816666',
                'check_out_latitude' => '-6.200500',
                'check_out_longitude' => '106.817000',
                'status' => 'hadir',
            ]);
        }
    }
}
