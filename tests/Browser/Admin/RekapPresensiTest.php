<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RekapPresensiTest extends DuskTestCase
{
    public function testEksportExcelRekapPresensi()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/') 
                    ->type('username', 'fagil') 
                    ->type('password', '123456') 
                    ->press('Login')
                    ->waitForLocation('/admin/dashboard', 10)
                    ->pause(1000)
                    ->visit('http://127.0.0.1:8000/admin/presensi')
                    ->assertPathIs('/admin/presensi')
                    ->pause(1000)
                    ->assertSee('Presensi')
                    ->pause(500)
                    ->assertVisible('#month')
                    ->pause(500)
                    ->select('#month','10')
                    ->assertVisible('#year')
                    ->pause(500)
                    ->select('#year','2024')
                    ->click('#exportButton')
                    ->pause(5000);
        });
    }

    public function testCetakRekapPresensi(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->assertVisible('#month')
                    ->pause(500)
                    ->select('#month','10')
                    ->assertVisible('#year')
                    ->pause(500)
                    ->select('#year','2024')
                    ->assertVisible('#user_id')
                    ->click('#printButton')
                    ->pause(5000);
        });
    }
}
