<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class KaryawanTest extends TestCase
{

    // Akses halaman karyawan tanpa login
    public function test_tidakbisa_akses_karyawan_tanpa_login()
    {
        $response = $this->get(route('admin.index.karyawan'));

        $response->assertRedirect(route('login'));
    }

    // Akses halaman karyawan setelah login
    public function test_akses_karyawan_setelah_login()
    {

        $response = $this->post('/',[
            'username' => 'fagil',
            'password' => '123456',
        ]);

        $response->assertStatus(302);
        
        $response = $this->get(route('admin.index.karyawan'));
        
        $response->assertStatus(200);
        $response->assertSee('Karyawan'); 
    }

    // Tambah data karyawan
    public function test_tambah_data_karyawan()
    {

        $response = $this->post('/',[
            'username' => 'fagil',
            'password' => '123456',
        ]);

        $response->assertStatus(302);

        $department = Department::where('kode_departemen','RJT')->first();

        $data = [
            'nik' => '000016',
            'username' => 'pramana',
            'department_id' => $department->id,
            'jabatan' => 'Direktur',
            'password'=> bcrypt('password'),
        ];

        $response = $this->post(route('admin.karyawan.add'), $data);
        $this->assertTrue(true, 'Berhasil Menambahkan Karyawan');

        $this->assertDatabaseHas('users', [
            'nik' => '000016',
            'username' => 'pramana',
        ]);

    }

    // // Tambah karyawan dengan field kosong
    // public function test_tambah_karyawan_dengan_field_kosong()
    // {
    //     $response = $this->post('/',[
    //         'username' => 'fagil',
    //         'password' => '123456',
    //     ]);
        
    //     $response->assertStatus(302);

    //     $data= [
    //         'nik'=>'',
    //         'username'=>'Yuna',
    //         'password'=>'',

    //     ];

    //     $response=$this->post(route('admin.karyawan.add'), $data);
    //     $response->assertSessionHasErrors(['nik','password']);
    // }

    // // Edit karyawan
    // public function test_edit_karyawan()
    // {

    //     $response = $this->post('/',[
    //         'username' => 'fagil',
    //         'password' => '123456',
    //     ]);
    //     $response->assertStatus(302);

    //     $users = User::where('nik','000016')->first();

    //     $data=[
    //         'edit_nik' => '000016',
    //         'edit_jabatan' => 'Part Time',
    //         'edit_password' => '',
    //     ];

    //     $response = $this->put(route('admin.karyawan.update', $users->id), $data);
    //     $this->assertTrue(true,'Karyawan Berhasil di Edit');
    //     $this->assertDatabaseHas('users',[
    //         'nik' => '000016',
    //         'jabatan' => 'Part Time'
    //     ]);
    // }

    // // Hapus karyawan
    // public function test_hapus_karyawan()
    // {
    //     $response = $this->post('/',[
    //         'username' => 'fagil',
    //         'password' => '123456',
    //         ]);

    //     $response->assertStatus(302);
    //     $karyawan = User::where('nik','000016')->first();

    //     $response = $this->delete(route('admin.karyawan.delete', $karyawan->id));
    //     $this->assertDatabaseMissing('users', [
    //         'id'=>$karyawan->id,
    //     ]);
    // }

    // public function test_cari_karyawan()
    // {

    // }
    
}


