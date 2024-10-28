<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    // Akses halaman departemen tanpa login
    public function test_akses_department_tanpa_login()
    {
        $response=$this->get(route('admin.index.departemen'));

        $response->assertRedirect(route('login'));
    }

    // Akses halaman departemen setelah login
    public function test_akses_departemen_setelah_login()
    {
        $response = $this->post('/',[
            'username' => 'fagil',
            'password' => '123456',
        ]);

        $response->assertStatus(302);
        $response = $this->get(route('admin.index.departemen'));

        $response->assertStatus(200);
        $response->assertSee('Departemen');
    }

    // Tambah data departemen
    public function test_tambah_data_departemen()
    {
        $response = $this->post('/',[
            'username' => 'fagil',
            'password' => '123456',
        ]);

        $response->assertStatus(302);

        $data = [
            'add_kode' => 'TST',
            'add_nama' => 'TAMBAH TESTING',
        ];

        $response = $this->post(route('admin.departemen.add'), $data);

        $response->assertStatus(302);
        $this->assertTrue(true, 'Data berhasil di tambah');

        $this->assertDatabaseHas('department', [
            'kode_departemen' => 'TST',
            'nama_departemen' => 'TAMBAH TESTING',
        ]);    
    }

    // Tambah departemen dengan field kosong
    public function test_tambah_department_dengan_field_kosong()
    {
        $response= $this->post('/',[
        'username' => 'fagil',
        'password' => '123456',
        ]);

        $data = [
            'add_kode' => '',
            'add_nama' => 'Test',
        ];

        $response=$this->post(route('admin.departemen.add'), $data);
        $response->assertSessionHasErrors('add_kode');
    }

    // Edit departemen
    public function test_edit_departemen()
    {
        $response = $this->post('/',[
            'username' => 'fagil',
            'password' => '123456',
        ]);

        $response->assertStatus(302);

        $departemen = Department::where('kode_departemen','TST')->first();

        $data = [
            'edit_kode' => 'TSTU',
            'edit_nama' => 'INI SUDAH DI EDIT',
        ];

        $response = $this->put(route('admin.departemen.update', $departemen->id), $data);
        $this->assertTrue(true, 'Departemen berhasil di Edit');
        $this->assertDatabaseHas('department', [
            'kode_departemen' => 'TSTU',
            'nama_departemen' => 'INI SUDAH DI EDIT',
        ]);

    }

    // Hapus departemen
    public function test_hapus_departemen(){
        $response = $this->post('/',[
            'username' => 'fagil',
            'password' => '123456',
        ]);

        $response->assertStatus(302);

        $departemen = Department::where('kode_departemen','TSTU')->first();

        $response=$this->delete(route('admin.departemen.delete', $departemen->id));
        $this->assertDatabaseMissing('department', [
            'id'=>$departemen->id,
        ]);
    }

    
}
