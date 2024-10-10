<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use Faker\Factory as Faker; // Pastikan Faker diimpor di sini

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // public function run(): void
    // {
    //     // \App\Models\User::factory(10)->create();

    //     // \App\Models\User::factory()->create([
    //     //     'name' => 'Test User',
    //     //     'email' => 'test@example.com',
    //     // ]);

    //     Department::create([
    //         'kode_departemen' => 'MPA',
    //         'nama_departemen'=> 'PT. Multi Power Abadi'
    //     ]);Department::create([
    //         'kode_departemen' => 'RJT',
    //         'nama_departemen'=> 'Rajata Wedding Organizer Islami'
    //     ]);
        
    //     User::create([
    //         'nik' => '0000001',
    //         'name' => 'Fagil Nuril Akbar',
    //         'username' => 'fagil',
    //         'jabatan' => 'Owner',
    //         'email' => 'fagil@test.com',
    //         'role' => 'admin',
    //         'department_id' => '1',
    //         'password' => Hash::make('123456')

    //     ]);  
    //     // User::create([
    //     //     'nik' => '0000002',
    //     //     'name' => 'Shania Yan',
    //     //     'username' => 'shania',
    //     //     'jabatan' => 'CEO',
    //     //     'email' => 'shanial@test.com',
    //     //     'role' => 'user',
    //     //     'department_id' => '2',
    //     //     'password' => Hash::make('123456')

    //     // ]);
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
}
