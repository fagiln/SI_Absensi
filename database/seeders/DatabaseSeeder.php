<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Department;
use App\Models\User;
use App\Models\Kehadiran;
use App\Models\Perizinan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

use Faker\Factory as Faker; // Pastikan Faker diimpor di sini

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         // \App\Models\User::factory(10)->create();

         // \App\Models\User::factory()->create([
         //     'name' => 'Test User',
         //     'email' => 'test@example.com',
         // ]);

        // Department::create([
        //     'kode_departemen' => 'MPA',
        //     'nama_departemen'=> 'PT. Multi Power Abadi'
        // ]);
        // Department::create([
        //     'kode_departemen' => 'RJT',
        //     'nama_departemen'=> 'Rajata Wedding Organizer Islami'
        // ]);
        
        // User::create([
        //     'nik' => '0000001',
        //     'name' => 'Admin',
        //     'username' => 'admin',
        //     'jabatan' => 'Owner',
        //     'email' => 'fagil@test.com',
        //     'role' => 'admin',
        //     'department_id' => '1',
        //     'password' => Hash::make('123456')

        // ]);  
        // $faker = Faker::create();
        // User::create([
        //     'nik' => $faker->unique()->numerify('######'),
        //     'name' => $faker->name,
        //     'username' => 'user5',
        //     'jabatan' => $faker->randomElement(['CEO', 'Manager', 'Supervisor', 'Staff', 'Engineer']),
        //     'email' => $faker->unique()->safeEmail,
        //     'no_hp' => $faker->phoneNumber,
        //     'role' => 'user',
        //     'department_id' => $faker->randomElement([1, 2]),
        //     'password' => Hash::make('user123')

        // ]);  
        $faker = Faker::create();
        User::create([
            'nik' => $faker->unique()->numerify('######'),
            'name' => $faker->name,
            'username' => 'user1',
            'jabatan' => $faker->randomElement(['CEO', 'Manager', 'Supervisor', 'Staff', 'Engineer']),
            'email' => $faker->unique()->safeEmail,
            'no_hp' => $faker->phoneNumber,
            'no_hp' => $faker->phoneNumber,
            'role' => 'user',
            'department_id' => $faker->randomElement([1, 2]),
            'password' => Hash::make('user123')

        ]);
    // }

    // faker data 
    // public function run()
    // {
    //     $faker = Faker::create();

    //     for ($i = 1; $i <= 10; $i++) {
    //         User::create([
    //             'nik' => $faker->unique()->numerify('0000#'),
    //             'name' => $faker->name,
    //             'username' => $faker->unique()->userName,
    //             'jabatan' => $faker->randomElement(['CEO', 'Manager', 'Supervisor', 'Staff', 'Engineer']),
    //             'email' => $faker->unique()->safeEmail,
    //             'role' => 'user',
    //             'department_id' => $faker->randomElement([1, 2]), // Acak antara 1 atau 2
    //             'password' => Hash::make('user123') // Password default 'user123'
    //         ]);
    //     }
    // }


    //     User::factory()->count(50)->create();
    // }
    
    $faker = Faker::create();
        
    // Temukan user dengan username 'user1'
    $user = User::where('username', 'user1')->first();
    
    if (!$user) {
        $this->command->info("User dengan username 'user1' tidak ditemukan.");
        return;
    }
    
    // Buat data kehadiran selama 1 bulan
    $startDate = Carbon::now()->subMonth()->startOfMonth();
    $endDate = Carbon::now()->subMonth()->endOfMonth();
    
    for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
        $checkInTime = $faker->dateTimeBetween($date->format('Y-m-d') . ' 08:00:00', $date->format('Y-m-d') . ' 09:10:00');
        $checkOutTime = $faker->dateTimeBetween($date->format('Y-m-d') . ' 17:00:00', $date->format('Y-m-d') . ' 18:00:00');
        $createdAt = $faker->dateTimeBetween($date->format('Y-m-d') . ' 08:00:00', $date->format('Y-m-d') . ' 09:10:00');
        $updatedAt = $faker->dateTimeBetween($date->format('Y-m-d') . ' 17:00:00', $date->format('Y-m-d') . ' 18:00:00');
    
        Kehadiran::create([
            'user_id' => $user->id,
            'work_date' => $date->format('Y-m-d'),
            'check_in_time' => $checkInTime,
            'check_out_time' => $checkOutTime,
            'check_in_photo' => $faker->imageUrl(),
            'check_out_photo' => $faker->imageUrl(),
            'check_in_latitude' => $faker->latitude,
            'check_in_longitude' => $faker->longitude,
            'check_out_latitude' => $faker->latitude,
            'check_out_longitude' => $faker->longitude,
            'status' => $faker->randomElement(['hadir', 'telat']),
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ]);
    }
    
    // Buat 3 data perizinan selama 1 bulan
    for ($i = 0; $i < 3; $i++) {
        $start_date = $faker->dateTimeBetween($startDate, $endDate);
        $end_date = (clone $start_date)->modify('+1 day'); // Misalkan izin selama 1 hari
        $createdAt = $faker->dateTimeBetween($start_date, $end_date);
    
        Perizinan::create([
            'user_id' => $user->id,
            'start_date' => $start_date->format('Y-m-d'),
            'end_date' => $end_date->format('Y-m-d'),
            'reason' => $faker->randomElement(['sakit', 'izin']),
            'keterangan' => $faker->sentence,
            'bukti_path' => $faker->imageUrl(),
            'status' => $faker->randomElement(['pending', 'diterima', 'ditolak']),
            'keterangan_ditolak' => $faker->boolean ? $faker->sentence : null,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
    }
    
        }
    

}
