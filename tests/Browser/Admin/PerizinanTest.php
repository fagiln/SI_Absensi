<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PerizinanTest extends DuskTestCase
{
    public function testFilterBerdasarkanTanggalPerizinan(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/') 
                    ->type('username', 'fagil') 
                    ->type('password', '123456') 
                    ->press('Login')
                    ->waitForLocation('/admin/dashboard', 10)
                    ->pause(1000)
                    ->visit('http://127.0.0.1:8000/admin/perizinan')
                    ->assertPathIs('/admin/perizinan')
                    ->pause(1000)
                    ->assertSee('Perizinan')
                    ->pause(500)
                    ->assertVisible('#filter_date')
                    ->pause(1000)
                    ->type('#filter_date', '05-11-2024')
                    ->pause(2000); 
        });
    }

    public function testEditStatusPerizinan()
{
    $this->browse(function (Browser $browser) {
        // Open the page and scroll to the table
        $browser->scrollIntoView('#perizinan-table_processing')
                ->waitFor('.pagination', 10)
                ->click('.pagination .page-link[data-dt-idx="0"]') 
                ->pause(1000);
        $browser->click('tbody tr:last-child td:last-child .btn.btn-info.mr-3')
                ->pause(2000)
                ->waitFor('#modalPerizinan_{{ $item->id }}', 5) // Adjust the ID if necessary
                ->assertVisible('#modalPerizinan_{{ $item->id }}');

        // Select 'Diterima' and submit
        $browser->within('#modalPerizinan_{{ $item->id }}', function (Browser $modal) {
            $modal->assertSee('Perizinan')
                  ->radio('status', 'diterima') // Adjust the name and value if necessary
                  ->press('Simpan');
        })
        ->pause(1000);

        // Check for a success message or assert the changes in the table if applicable
        $browser->waitFor('#success-alert', 5)
                ->assertSeeIn('#success-alert', 'Status updated successfully'); // Adjust the message text if necessary
    });
}




    public function testFilterBerdasarkanAwalCuti()
    {
        $this->browse(function (Browser $browser) {
            $browser->assertVisible('#filter_start_date')
                    ->pause(1000)
                    ->type('#filter_start_date', '28-10-2024')
                    ->pause(2000); 
        });
    }

    public function testFilterBerdasarkanStatusPerizinan()
    {
        $this->browse(function (Browser $browser) {
            $browser->assertVisible('#filter_status')
                    ->pause(1000)
                    ->select('#filter_status', 'Ditolak')
                    ->pause(2000); 
        });
    }

    
}
