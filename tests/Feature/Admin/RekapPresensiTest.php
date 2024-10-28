<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RekapPresensiTest extends TestCase
{
    public function test_tidakbisa_akses_rekap_presensi_tanpa_login()
    {
        $response = $this->get(route('admin.rekap-presensi.index'));

        $response->assertRedirect(route('login'));
    }

    
    public function test_akses_rekap_presensi_setelah_login()
    {

        $response = $this->post('/',[
            'username' => 'fagil',
            'password' => '123456',
        ]);

        $response->assertStatus(302);
        
        $response = $this->get(route('admin.rekap-presensi.index'));
        
        $response->assertStatus(200);
        $response->assertSee('Rekap Presensi'); 
    }

    public function test_pilih_data_rekap_yang_akan_dieksport()
    {
        $response = $this->post('/',[
            'username'=>'fagil',
            'password'=>'123456',
        ]);

        $response->assertStatus(302);
        $response=$this->get(route('admin.rekap-presensi.export',[
            'month'=>'9',
            'year'=>'2024',
        ]));

        $response->assertStatus(200);
        $response->assertDownload();
    }

    public function test_pilih_data_yang_akan_dicetak()
    {
        $response = $this->post('/',[
            'username'=>'fagil',
            'password'=>'123456',
        ]);

        $response->assertStatus(302);
        $response=$this->get(route('admin.rekap-presensi.print',[
            'month'=>'9',
            'year'=>'2024',
        ]));

        $response->assertStatus(200);
    }
}
