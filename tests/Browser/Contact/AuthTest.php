<?php

namespace Tests\Browser\Contact;

use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthTest extends DuskTestCase
{
    use WithFaker;

    /**
     * @throws \Throwable
     */
    public function testAuth(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::first())
                ->visit('/kontak')
                ->assertTitleContains('Kontak')
                ->type('message', $this->faker->paragraph)
                ->scrollTo('#action-buttons')
                ->click('@send-button')
                ->assertSee('terima kasih');
        });
    }
}
