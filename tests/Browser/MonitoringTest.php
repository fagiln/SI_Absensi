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
    public function testMonitoring(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/')
                    ->type('username','fagil')
                    ->type('password','123456')
                    ->press('login')
                    ->waitForLocation('/admin/dashboard',10)
                    ->pause(1000)
                    ->visit('http://127.0.0.1:8000/admin/monitoring')
                    ->assertPathIs('/admin/monitorng')
                    ->pause(1000)
                    ->assertSee('Monitoring');
        });
    }
}
