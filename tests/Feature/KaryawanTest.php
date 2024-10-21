<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class KaryawanTest extends TestCase
{

    public function test_tidakbisa_akses_karyawan_tanpa_login()
    {
        // Coba akses halaman tanpa login
        $response = $this->get(route('admin.index.karyawan'));

        // Pastikan redirect ke halaman login
        $response->assertRedirect(route('login'));
    }

    public function test_akses_karyawan_setelah_login()
    {
        User::create([
            'nik' => '000018',
            'username' => 'testuser',
            'password'=> bcrypt('password')
        ]);

        // Login menggunakan user yang dibuat
        $this->actingAs(User::first());

        // Akses halaman setelah login
        $response = $this->get(route('admin.index.karyawan'));

        // Pastikan halaman dapat diakses
        $response->assertStatus(200);
        $response->assertSee('Karyawan');
    }

    public function test_tambah_data_karyawan()
    {
        User::create([
            'nik' => '000019',
            'username' => 'testuser2',
            'password'=> bcrypt('password'),
        ]);

        $this->actingAs(User::first());

        $department = Department::create([
            'kode_departemen' => 'RMD',
            'nama_departemen' => 'Ramada Event Organizer',
        ]);

        $data = [
            'nik' => '000020',
            'username' => 'bumi',
            'department_id' => $department->id,
            'password'=> bcrypt('password'),
        ];

        $response = $this->post(route('admin.karyawan.add'), $data);

        $this->assertDatabaseHas('users', [
            'nik' => '000020',
            'username' => 'bumi',
        ]);

    }

    public function test_edit_karyawan()
    {

        $department = Department::create([
            'kode_departemen' => 'Mark',
            'nama_departemen' => 'Multi Corporation',
        ]);

        $users = User::create([
            'nik' => '000022',
            'username' => 'usertest2',
            'department_id' => $department->id,
            'password'=> bcrypt('password'),
        ]);

        $this->actingAs(User::first());

        $data=[
            'edit_nik' => '000022',
            'edit_jabatan' => 'Internship',
            'edit_password' => '',
        ];

        $response = $this->put(route('admin.karyawan.update', $users->id), $data);
        $this->assertDatabaseHas('users',[
            'nik' => '000022',
            'jabatan' => 'internship'
        ]);
    }

    public function test_hapus_karyawan()
    {
        $department = Department::create([
            'kode_departemen' => 'Test',
            'nama_departemen' => 'Testing Dep',
        ]);
        
        $karyawan = User::create([
            'nik' => '000023',
            'username' => 'usertest3',
            'department_id' => $department->id,
            'password'=> bcrypt('password'),
        ]);
        
        $this->actingAs(User::first());

        $response = $this->delete(route('admin.karyawan.delete', $karyawan->id));
        $this->assertDatabaseMissing('users', [
            'id'=>$karyawan->id,
        ]);

    }

    public function test_cari_karyawan()
    {

    }
    
}


