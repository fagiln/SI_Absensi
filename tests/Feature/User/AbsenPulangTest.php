<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AbsenPulangTest extends TestCase
{
    public function test_akses_home_tanpa_login()
    {
        $response=$this->get(route('home'));
        $response->assertRedirect(route('login'));
    }

    // akses home setelah login
    public function test_akses_home_setelah_login()
    {
        $response=$this->post('/',[
            'username'=>'user3',
            'password'=>'user123',
        ]);

        $response->assertStatus(302);
        $response=$this->get(route('absen_pulang'));
        $response->assertStatus(200);
        $response->assertSee('Lokasi Anda Saat Ini:');
    }

    // absen pulang 
    public function test_absen_pulang()
    {
        $response=$this->post('/',[
            'username'=>'user3',
            'password'=>'user123',
        ]);

        $data = [
            'photo-data' => 'absen_out_17_20241105.png',
            'latitude' => '-7.066064',
            'longitude' => '111.960328',
        ];

        // Post attendance data
        $response = $this->post(route('absen_pulang.store'), $data);

        // Check for redirection
        $response->assertStatus(302);

        // $this->assertDatabaseHas('kehadiran', [
        //     'user_id' => 3,
        //     'check_in_photo' => 'absen_in_17_20241105.png',
        // ]);
    }
}

