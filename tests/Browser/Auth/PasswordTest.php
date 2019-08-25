<?php

namespace Tests\Browser\Auth;

use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PasswordTest extends DuskTestCase
{
    use WithFaker;

    /**
     * @throws \Throwable
     */
    public function testEmail(): void
    {
        $this->browse(function (Browser $browser) {
            $user = factory(User::class)->create([
                'email' => $this->faker->safeEmail,
            ]);

            $browser->visit('/lupa-sandilewat')
                ->type('email', $user->email)
                ->click('@reset-button')
                ->assertSee('Berhasil!');
        });
    }
}
