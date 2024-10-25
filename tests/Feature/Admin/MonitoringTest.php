<?php

namespace Tests\Feature;
use App\Models\Kehadiran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MonitoringTest extends TestCase
{
    
    public function test_akses_monitoring_tanpa_login()
    {
        $response=$this->get(route('admin.monitoring.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_akses_monitoring_setelah_login()
    {
        $response=$this->post('/',[
            'username'=>'fagil',
            'password'=>'123456',
        ]);

        $response->assertStatus(302);
        $response=$this->get(route('admin.monitoring.index'));
        $response->assertStatus(200);
        $response->assertSee('Monitoring');

    }

    public function test_filter_berdasarkan_tanggal()
    {
        $response = $this->post('/',[
            'username' => 'fagil',
            'password' => '123456',
        ]);

        $response->assertStatus(302);
        $response=$this->get(route('admin.monitoring.index', ['filter_date'=>'2024-10-24']));
        $response->assertStatus(200);
        $response->assertSee('Reyna Hackett');
    }
}
