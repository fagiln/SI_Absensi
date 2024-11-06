<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AbsenMasukTest extends DuskTestCase
{

    public function testAbsenMasuk(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/')
                    ->type('username', 'user3') 
                    ->type('password', 'user123') 
                    ->press('Login')
                    ->waitForLocation('/user/home', 10)
                    ->pause(1000)
                    ->click('.btn.btn-masuk')
                    ->visit('http://127.0.0.1:8000/user/absen_masuk')
                    ->assertPathIs('/user/absen_masuk')
                    ->pause(3000)
                    ->driver->executeScript('navigator.mediaDevices.getUserMedia = async () => new MediaStream();');
            
            $browser->waitFor('.absen-button.mt-3', 10)
                    ->click('.absen-button.mt-3')
                    ->waitFor('.absen-button.mr-2.mt-3',10)
                    ->click('.absen-button.mr-2.mt-3');

        });
    }
}
