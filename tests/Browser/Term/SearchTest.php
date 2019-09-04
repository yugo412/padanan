<?php

namespace Tests\Browser\Term;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SearchTest extends DuskTestCase
{
    /**
     * @throws \Throwable
     */
    public function testSearchIndex()
    {
        $this->browse(function (Browser $browser) {
            $keyword = 'test';

            $browser->visit('/')
                ->type('e', $keyword)
                ->click('@search-button')
                ->assertTitleContains($keyword)
                ->assertSee($keyword)
                ->assertSee('hasil pencarian');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testSearchAnywhere()
    {
        $this->browse(function (Browser $browser) {
            $keyword = 'test';

            $browser->visit('/tambah')
                ->type('e', $keyword)
                ->click('@search-button')
                ->assertTitleContains($keyword)
                ->assertSee($keyword)
                ->assertSee('hasil pencarian');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testSearchLegacy()
    {
        $this->browse(function (Browser $browser) {
            $keyword = 'test';

            $browser->visit('/cari?katakunci=' . $keyword)
                ->assertTitleContains($keyword)
                ->assertSee($keyword)
                ->assertSee('hasil pencarian');
        });
    }
}
