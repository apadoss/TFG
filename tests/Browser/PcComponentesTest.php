<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PcComponentesTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testScrapingTarjetasGraficas()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://www.pccomponentes.com/tarjetas-graficas')
                    ->pause(5000);

            $html = $browser->driver->getPageSource();

            dump($html);
        });
    }
}
