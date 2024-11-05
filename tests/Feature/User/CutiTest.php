<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CutiTest extends TestCase
{
    public function test_akses_cuti_tanpa_login()
    {
        $response = $this->get(route('cuti'));

        $response->assertRedirect(route('login'));
    }

    public function test_akses_cuti_setelah_login()
    {
        $response=$this->post('/',[
            'username'=>'user3',
            'password'=>'user123',
        ]);

        $response->assertStatus(302);
        $response=$this->get(route('cuti'));
        $response->assertStatus(200);
        $response->assertSee('Formulir Pengajuan Cuti');
    }

    public function test_pengajuan_cuti()
    {

        $response = $this->post('/', [
            'username' => 'user3',
            'password' => 'user123',
        ]);

        $response->assertStatus(302);

    
        $data = [
            'filter_izin' => 'izin',
            'start_date' => '2024-11-09',
            'end_date' => '2024-11-12',
            'alasan' => 'acara keluarga',
            'file' => UploadedFile::fake()->image('17_202411011309.pdf') 
        ];

        $response = $this->post(route('cuti.create'), $data);

        $response->assertStatus(302);

        $this->assertDatabaseHas('perizinan', [
            'user_id' => '3', 
            'keterangan' => 'acara keluarga',
        ]);
    }

}
