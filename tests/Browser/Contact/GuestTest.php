<?php

namespace Tests\Browser\Contact;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GuestTest extends DuskTestCase
{
    use WithFaker;

    /**
     * @throws \Throwable
     */
    public function testForm(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/kontak')
                ->assertTitleContains('Kontak')
                ->type('name', $this->faker->name)
                ->type('email', $this->faker->email)
                ->type('message', $this->faker->paragraph)
                ->scrollTo('#action-buttons')
                ->click('@send-button')
                ->assertSee('terima kasih');
        });
    }
}
