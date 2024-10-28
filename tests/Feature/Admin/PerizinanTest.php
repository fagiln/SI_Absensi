<?php

namespace Tests\Feature\Admin;

use App\Models\Perizinan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PerizinanTest extends TestCase
{
    public function test_akses_perizinan_tanpa_login()
    {
        $response=$this->get(route('admin.perizinan.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_akses_perizinan_setelah_login()
    {
        $response=$this->post('/',[
            'username'=>'fagil',
            'password'=>'123456',
        ]);

        $response->assertStatus(302);
        $response=$this->get(route('admin.perizinan.index'));
        $response->assertStatus(200);
        $response->assertSee('Perizinan');

    }

    public function test_filter_tanggal_pembuatan()
    {
        $response = $this->post('/',[
            'username'=>'fagil',
            'password'=>'123456',
        ]);

        $response->assertStatus(302);
        $response=$this->get(route('admin.perizinan.index', ['filter_date'=>'2024-10-24']));
        $response->assertStatus(200);
        $response->assertSee('diterima');

    }

    public function test_filter_awal_cuti()
    {
        $response=$this->post('/',[
            'username'=>'fagil',
            'password'=>'123456',
        ]);

        $response->assertStatus(302);
        $response=$this->get(route('admin.perizinan.index',['filter_start_date'=>'2024-10-28']));
        $response->assertStatus(200);
        $response->assertSee('pending');
    }

    public function test_filter_karyawan()
    {
        $response=$this->post('/',[
            'username'=>'fagil',
            'password'=>'123456',
        ]);

        $response->assertStatus(302);
        $response=$this->get(route('admin.perizinan.index', ['filter_status'=>'ditolak']));
        $response->assertStatus(200);
        $response->assertSee('ditolak');
    }

    public function test_edit_status_cuti_menjadi_ditolak()
    {
        $response=$this->post('/',[
            'username'=>'fagil',
            'password'=>'123456',
        ]);

        $response->assertStatus(302);
        $perizinan = Perizinan::find(3);

        $response = $this->put(route('admin.perizinan.status', $perizinan->id), [
            'status' => 'ditolak',
            'reason' => 'Alasan penolakan test',
        ]);
    
        $response->assertStatus(302);
        $response->assertSessionHas('status', 'Berhasil Mengedit status');
        $this->assertDatabaseHas('perizinan',[
            'id'=>$perizinan->id,
            'status'=>'ditolak',
            'keterangan_ditolak'=>'Alasan penolakan test',
        ]);
        
    }

    public function test_edit_status_cuti_diterima()
    {
        $response=$this->post('/',[
            'username'=>'fagil',
            'password'=>'123456',
        ]);

        $response->assertStatus(302);
        $perizinan=Perizinan::where('user_id','23')->first();
        $response=$this->put(route('admin.perizinan.status', $perizinan->id),[
            'status'=>'diterima',
        ]);
        $response->assertStatus(302);
    }
}
