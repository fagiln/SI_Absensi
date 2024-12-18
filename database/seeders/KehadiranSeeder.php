<?php

namespace Database\Seeders;

use App\Models\Kehadiran;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

use Faker\Factory as Faker; // Pastikan Faker diimpor di sini

class KehadiranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     // Misalnya, kita memiliki 10 user yang ingin kita isi dengan data kehadiran
    //     $users = User::all();

    //     foreach ($users as $user) {
    //         // Buat data kehadiran untuk setiap user
    //         Kehadiran::create([
    //             'user_id' => $user->id,
    //             'work_date' => Carbon::now()->toDateString(),
    //             'check_in_time' => Carbon::now()->subHours(rand(1, 3))->toDateTimeString(),
    //             'check_out_time' => Carbon::now()->toDateTimeString(),
    //             'check_in_photo' => 'checkin_photo_' . $user->id . '.jpg',
    //             'check_out_photo' => 'checkout_photo_' . $user->id . '.jpg',
    //             'check_in_latitude' => '-6.200000',
    //             'check_in_longitude' => '106.816666',
    //             'check_out_latitude' => '-6.200500',
    //             'check_out_longitude' => '106.817000',
    //             'status' => 'hadir',
    //         ]);
    //     }
    // }

    // public function run()
    // {
    //     // Ambil semua user
    //     $users = User::take(10)->get();
    //     $faker = Faker::create();
    //     $tanggal_kehadiran = '2024-10-10'; // Set tanggal 10 Oktober 2024

    //     foreach ($users as $user) {
    //         // Buat data kehadiran untuk setiap user pada tanggal 10/10/2024
    //         Kehadiran::create([
    //             'user_id' => $user->id,
    //             'work_date' => $tanggal_kehadiran,
    //             'check_in_time' => Carbon::parse($tanggal_kehadiran . ' ' . $faker->time('H:i:s', '08:00:00'))->toDateTimeString(),
    //             'check_out_time' => Carbon::parse($tanggal_kehadiran . ' ' . $faker->time('H:i:s', '17:00:00'))->toDateTimeString(),
    //             'check_in_photo' => 'checkin_photo_' . $user->id . '.jpg',
    //             'check_out_photo' => 'checkout_photo_' . $user->id . '.jpg',
    //             'check_in_latitude' => $faker->latitude(-6.21, -6.19),
    //             'check_in_longitude' => $faker->longitude(106.81, 106.82),
    //             'check_out_latitude' => $faker->latitude(-6.21, -6.19),
    //             'check_out_longitude' => $faker->longitude(106.81, 106.82),
    //             'status' => 'hadir',
    //         ]);
    //     }
    // }

    public function run(){
        // Buat data kehadiran untuk "User 2" dengan kondisi dari bulan 9 sampai Januari 2025
    $user2 = User::where('id', 2)->first();
    $faker = Faker::create();
    $startDate = Carbon::create(2024, 9, 1); // Tanggal mulai: 1 September 2024
    $endDate = Carbon::create(2025, 1, 31); // Tanggal akhir: 31 Januari 2025

    // Buat data kehadiran untuk User 2
    $attendanceCount = 0; // Counter for attendance records
    // Buat data kehadiran untuk User 2
    $attendanceCount = 0; // Counter for attendance records

    while ($startDate <= $endDate && $attendanceCount < 10) {
        // Buat data kehadiran hanya jika kita belum mencapai 10 catatan
        Kehadiran::create([
            'user_id' => $user2->id,
            'work_date' => $startDate->toDateString(), // Ambil tanggal sebagai string
            'check_in_time' => Carbon::parse($startDate->toDateString() . ' ' . $faker->time('H:i:s', '08:00:00'))->toDateTimeString(),
            'check_out_time' => Carbon::parse($startDate->toDateString() . ' ' . $faker->time('H:i:s', '17:00:00'))->toDateTimeString(),
            'check_in_photo' => 'checkin_photo_' . $user2->id . '.jpg',
            'check_out_photo' => 'checkout_photo_' . $user2->id . '.jpg',
            'check_in_latitude' => $faker->latitude(-6.21, -6.19),
            'check_in_longitude' => $faker->longitude(106.81, 106.82),
            'check_out_latitude' => $faker->latitude(-6.21, -6.19),
            'check_out_longitude' => $faker->longitude(106.81, 106.82),
            'status' => 'hadir',
        ]);

        // Tambah satu hari untuk kehadiran berikutnya
        $startDate->addDay();
        $attendanceCount++; // Increment the attendance counter
    }
    }
}
