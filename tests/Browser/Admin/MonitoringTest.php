<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MonitoringTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testFilterBerdasarkanTanggal(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/') 
                    ->type('username', 'fagil') 
                    ->type('password', '123456') 
                    ->press('Login')
                    ->waitForLocation('/admin/dashboard', 10)
                    ->pause(1000)
                    ->visit('http://127.0.0.1:8000/admin/monitoring')
                    ->assertPathIs('/admin/monitoring')
                    ->pause(1000)
                    ->assertSee('Monitoring')
                    ->pause(500)
                    ->assertVisible('#filter_date')
                    ->pause(1000)
                    ->type('#filter_date', '24-10-2024')
                    ->pause(2000)
                    ->assertSee('Reyna Hackett'); 
        });
    }

    public function testLihatKaryawanCutiHariIni()
    {
        $this->browse (function(Browser $browser){
            $browser->press('Lihat Karyawan Cuti Hari Ini')
                ->pause(1000)
                ->waitFor('#modalKaryawanCuti', 10) 
                ->assertVisible('#modalKaryawanCuti')
                ->within('#modalKaryawanCuti', function (Browser $browser) {
                    $browser->assertSee('Daftar Karyawan Cuti') 
                            ->pause(1000) 
                            ->click('.btn.btn-secondary') 
                            ->waitForLocation('/admin/monitoring', 10)
                            ->pause(1000);
            });
        });
    }

    public function testSearch()
    {
    $this->browse(function (Browser $browser) {
        // Step 1: Uji dengan pencarian yang ada
        $browser->type('input[type=search]', '081251')
                ->pause(1000) 
                ->assertSee('Reyna Hackett') 
                ->assertDontSee('Bumi') 
                ->pause(1000);

        // Step 3: Uji dengan pencarian yang tidak ada
        $browser->type('input[type=search]', 'namaTidakAda') 
                ->pause(1000)
                ->assertSee('No matching records found') 
                ->pause(1000);
    });
    }

}
