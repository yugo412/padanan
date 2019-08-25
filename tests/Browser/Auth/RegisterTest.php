<?php

namespace Tests\Browser\Auth;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterTest extends DuskTestCase
{
    use WithFaker;

    /**
     * @throws \Throwable
     */
    public function testForm(): void
    {
        $this->browse(function (Browser $browser) {
            $email = $this->faker->email;

            $browser->visit('/daftar')
                ->assertSee('Daftar')
                ->type('name', $this->faker->name)
                ->type('email', $email)
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->click('@register-button')
                ->assertPathIs('/home');
        });
    }
}
