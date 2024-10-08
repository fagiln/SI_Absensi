<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        Department::create([
            'kode_departemen' => 'MPA',
            'nama_departemen'=> 'PT. Multi Power Abadi'
        ]);
        
        User::create([
            'nik' => '0000001',
            'name' => 'Fagil Nuril Akbar',
            'username' => 'fagil',
            'jabatan' => 'Owner',
            'email' => 'fagil@test.com',
            'role' => 'admin',
            'department_id' => '1',
            'password' => Hash::make('123456')

        ]);  
        User::create([
            'nik' => '0000002',
            'name' => 'Shania Yan',
            'username' => 'shania',
            'jabatan' => 'CEO',
            'email' => 'shanial@test.com',
            'role' => 'user',
            'department_id' => '2',
            'password' => Hash::make('123456')

        ]);
    }
}
