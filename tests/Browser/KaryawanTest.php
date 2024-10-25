<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class KaryawanTest extends DuskTestCase
{
    // Test Tambah Karyawan
    public function testTambahKaryawan(): void
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Login if necessary
            $browser->visit('http://127.0.0.1:8000/') 
                    ->type('username', 'fagil')
                    ->type('password', '123456') 
                    ->press('Login')
                    ->waitForLocation('/admin/dashboard', 10) 
                    ->pause(1000)
                    ->visit('http://127.0.0.1:8000/admin/karyawan') 
                    ->assertPathIs('/admin/karyawan') 

                    // Step 2: Buka modal dan isi form
                    ->click('.btn.btn-custom.mb-3') 
                    ->pause(1000) 

                    // Step 3: Isi formulir di dalam modal
                    ->within('#modal', function (Browser $browser) {
                        $browser->type('nik', '000080') 
                                ->type('username', 'bambang') 
                                ->select('department_id', '1') 
                                ->type('password', 'abcdefg') 
                                ->press('Tambah'); 
                    })
                    // ->pause(1000);
                    ->waitForLocation('/admin/karyawan', 10);
                    // ->waitForText('Berhasil Menambahkan Karyawan')
                    // ->assertSee('Berhasil Menambahkan Karyawan')
                    // ->pause(1000);
        });
    }


    // Test Edit Karyawan
    // public function testEditKaryawan()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->scrollIntoView('#karyawan-table')
    //                 ->waitFor('.pagination', 10)
    //                 ->click('.pagination .page-link[data-dt-idx="0"]') 
    //                 ->pause(1000)
    //                 ->click('tbody tr:last-child td:last-child .btn.btn-warning') 
    //                 ->pause(2000)
    //                 ->waitFor('#modalEdit', 5)
    //                 ->within('#modalEdit', function (Browser $browser) {
    //                     $browser->type('edit_nik', '000090') 
    //                             ->type('edit_jabatan', 'Arsitek') 
    //                             ->press('Edit');
    //                 })
    //                 ->pause(1000)
    //                 ->assertSee('Karyawan Berhasil di Edit'); 
    //     });
    // }



    // // Test Hapus Karyawan
    // public function testHapusKaryawan()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->scrollIntoView('#karyawan-table')
    //                 ->waitFor('.pagination', 10)
    //                 ->click('.pagination .page-link[data-dt-idx="0"]') 
    //                 ->pause(1000)
    //                 ->click('tbody tr:last-child td:last-child .btn.btn-danger') 
    //                 ->pause(1000)
    //                 ->acceptDialog()
    //                 ->pause(3000);

    //                 });
    // }

    // public function testSearch()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->type('input[type=search]', 'user3')
    //                 ->pause(1000) 
    //                 ->assertSee('Nicolas Fadel') 
    //                 ->assertDontSee('Bumi') 
    //                 ->pause(1000);

    //         // Step 3: Uji dengan pencarian yang tidak ada
    //         $browser->type('input[type=search]', 'namaTidakAda') 
    //                 ->pause(1000)
    //                 ->assertSee('No matching records found') 
    //                 ->pause(1000);

    
    // });
    // }
}


