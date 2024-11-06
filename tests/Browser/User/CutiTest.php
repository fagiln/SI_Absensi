<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CutiTest extends DuskTestCase
{
    public function testPengajuanCuti(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/')
                    ->type('username', 'user3') 
                    ->type('password', 'user123') 
                    ->press('Login')
                    ->waitForLocation('/user/home', 10)
                    ->pause(1000)
                    ->visit('http://127.0.0.1:8000/user/cuti')
                    ->assertPathIs('/user/cuti')
                    ->pause(1000)
                    ->assertVisible('#filter_izin')
                    ->pause(500)
                    ->select('filter_izin', 'sakit')
                    ->type('start_date','11-11-2024')
                    ->type('end_date','14-11-2024')
                    ->type('alasan','masuk angin')
                    ->attach('file', base_path('public/asset/Background_2.png'))
                    ->pause(500);
                    
            $browser->script("document.querySelector('button[type=submit]').scrollIntoView();");

            $browser->pause(500)
                    ->press('Ajukan')
                    ->pause(2000);
        });
    }
}
