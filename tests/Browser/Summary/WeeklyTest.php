<?php

namespace Tests\Browser\Summary;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class WeeklyTest extends DuskTestCase
{
    /**
     * @throws \Throwable
     */
    public function testView()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/ringkasan-mingguan')
                ->assertSee('Ringkasan Mingguan')
                ->assertSee('Istilah baru')
                ->assertSee('Pencarian baru di Twitter')
                ->assertSee('Kueri pencarian')
                ->assertSee('Kontributor baru');
        });
    }
}
