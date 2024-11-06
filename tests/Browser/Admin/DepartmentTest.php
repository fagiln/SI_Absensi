<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DepartmentTest extends DuskTestCase
{
    // Test Tambah Departemen
    public function testTambahDepartment(): void
{
    $this->browse(function (Browser $browser) {
        // Step 1: Login
        $browser->visit('http://127.0.0.1:8000/') 
                ->type('username', 'fagil') 
                ->type('password', '123456') 
                ->press('Login')
                ->waitForLocation('/admin/dashboard', 10)
                ->pause(1000)
                
                // Step 2: Navigasi ke halaman departemen
                ->visit('http://127.0.0.1:8000/admin/departemen') 
                ->assertPathIs('/admin/departemen') 

                // Step 3: Buka modal untuk tambah departemen
                ->click('.btn.btn-custom.mb-3') 
                ->pause(1000) 

                // Step 4: Isi formulir di dalam modal
                ->within('#modalAdd', function (Browser $browser) {
                    $browser->type('add_nama', 'Ramada Event Organizer') 
                            ->type('add_kode', 'RMD') 
                            ->press('Tambah')
                            ->pause(1000);
                })
                
                // Step 5: Verifikasi bahwa data berhasil ditambahkan
                ->waitForLocation('/admin/departemen', 10)
                ->pause(1000); 
        });
    }


    // Test Edit Departemen
    public function testEditKaryawan()
    {
        $this->browse(function (Browser $browser) {
            $browser->scrollIntoView('#departemen-table')
                    ->waitFor('.pagination', 10)
                    ->click('.pagination .page-link[data-dt-idx="0"]') 
                    ->pause(1000)
                    ->click('tbody tr:last-child td:last-child .btn.btn-warning') 
                    ->pause(2000)
                    ->waitFor('#modalEdit', 5)
                    ->within('#modalEdit', function (Browser $browser) {
                        $browser->type('edit_nama', 'Ramada EO') 
                                ->type('edit_kode', 'REO') 
                                ->press('Edit');
                    })
                    ->pause(1000)
                    ->assertSee('Departemen berhasil di Edit'); 
        });
    }



    // Test Hapus Departemen
    public function testHapusDepartemen()
    {
        $this->browse(function (Browser $browser) {
            $browser->scrollIntoView('#departemen-table')
                    ->waitFor('.pagination', 10)
                    ->click('.pagination .page-link[data-dt-idx="0"]') 
                    ->pause(1000)
                    ->click('tbody tr:last-child td:last-child .btn.btn-danger') 
                    ->pause(1000)
                    ->acceptDialog()
                    ->pause(3000);
                    });
    }

    public function testSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->type('input[type=search]', 'Nains')
                    ->pause(1000)
                    ->assertSee('NSM') 
                    ->assertDontSee('RJT') 
                    ->pause(1000);

            // Step 3: Uji dengan pencarian yang tidak ada
            $browser->type('input[type=search]', 'namaTidakAda')
                    ->pause(1000)
                    ->assertSee('No matching records found')
                    ->pause(1000);

    
    });
    }
}


