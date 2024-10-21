<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DepartmentTest extends TestCase
{

    
    // public function test_akses_departemen_setelah_login(): void
    // {
    //     User::create([
    //         'nik' => '000027',
    //         'username'  => 'TestDep',
    //         'password' => bcrypt('password'),
    //     ]);

    //     $this->actingAs(User::first());
    //     $response = $this->get(route('admin.index.departemen'));

    //     $response->assertStatus(200);
    //     $response->assertSee('Departemen');
    // }

    // public function test_tambah_data_departemen()
    // {
    //     User::create([
    //         'nik' => '000029',
    //         'username'  => 'TestDepar',
    //         'password' => bcrypt('password'),
    //     ]);

    //     $this->actingAs(User::first());

    //     $data = [
    //         'add_kode' => 'TST',
    //         'add_nama' => 'TAMBAH TESTING',
    //     ];

    //     $response = $this->post(route('admin.departemen.add'), $data);

    //     $response->assertStatus(302);

    //     $this->assertDatabaseHas('department', [
    //         'kode_departemen' => 'TST',
    //         'nama_departemen' => 'TAMBAH TESTING',
    //     ]);    
    // }

    // public function test_edit_departemen()
    // {
    //     User::create([
    //         'nik' => '000028',
    //         'username' => 'TestDepar2',
    //         'password' => bcrypt('password'),
    //     ]);

    //     $this->actingAs(User::first());

    //     $departemen = Department::create([
    //         'kode_departemen' => 'TST2',
    //         'nama_departemen' => 'Test Department',
    //     ]);

    //     $data = [
    //         'edit_kode' => 'TSTU',
    //         'edit_nama' => 'EDIT TESTING',
    //     ];

    //     $response = $this->put(route('admin.departemen.update', $departemen->id), $data);
    //     $this->assertDatabaseHas('department', [
    //         'kode_departemen' => 'TSTU',
    //         'nama_departemen' => 'EDIT TESTING',
    //     ]);

    // }

    public function test_hapus_departemen(){
        User::create([
            'nik' => '000027',
            'username' => 'TestDepar3',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs(User::first());

        $departemen = Department::create([
            'kode_departemen' => 'TST3',
            'nama_departemen' => 'HAPUS TESTING',
        ]);

        $response=$this->delete(route('admin.departemen.delete', $departemen->id));
        $this->assertDatabaseMissing('department', [
            'id'=>$departemen->id,
        ]);
    }

    
}
