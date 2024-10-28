<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PresensiTest extends TestCase
{
    public function test_tidakbisa_akses_presensi_tanpa_login()
    {
        $response = $this->get(route('admin.presensi.index'));

        $response->assertRedirect(route('login'));
    }

    
    public function test_akses_presensi_setelah_login()
    {

        $response = $this->post('/',[
            'username' => 'fagil',
            'password' => '123456',
        ]);

        $response->assertStatus(302);
        
        $response = $this->get(route('admin.presensi.index'));
        
        $response->assertStatus(200);
        $response->assertSee('Karyawan'); 
    }

    public function test_pilih_data_yang_akan_dieksport()
    {
        $response = $this->post('/',[
            'username'=>'fagil',
            'password'=>'123456',
        ]);

        $response->assertStatus(302);
        $response=$this->get(route('admin.presensi.export',[
            'month'=>'October',
            'year'=>'2024',
            'user_id'=>'17',
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
        $response=$this->get(route('admin.presensi.print',[
            'month'=>'October',
            'year'=>'2024',
            'user_id'=>'17',
        ]));

        $response->assertStatus(200);
    }

    
}
