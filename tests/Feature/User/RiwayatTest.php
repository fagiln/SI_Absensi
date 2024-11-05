<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RiwayatTest extends TestCase
{
  
    public function test_akses_riwayat_tanpa_login()
    {
        $response = $this->get(route('riwayat'));

        $response->assertRedirect(route('login'));
    }

    public function test_akses_riwayat_setelah_login()
    {
        $response = $this->post('/',[
            'username'=>'user3',
            'password'=>'user123',
        ]);

        $response->assertStatus(302);
        $response=$this->get(route('riwayat'));
        $response->assertStatus(200);
        $response->assertSee('Tabel Kehadiran Karyawan');
    }

    public function test_cari_riwayat_presensi()
    {
        $response = $this->post('/',[
            'username'=>'user3',
            'password'=>'user123',
        ]);

        $response->assertStatus(302);
        $response=$this->get(route('riwayat',[
            'start_date'=>'2024-10-01',
            'end_date'=>'2024-10-31',
            'filter'=>'Izin',
        ]));
        $response->assertStatus(200);
    }
}
