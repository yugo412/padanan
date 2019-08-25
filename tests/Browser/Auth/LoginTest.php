<?php

namespace Tests\Browser\Auth;

use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
    use WithFaker;

    /**
     * @throws \Throwable
     */
    public function testForm(): void
    {
        Auth::logout();
        $this->browse(function (Browser $browser) {
            $user = factory(User::class)->create([
                'email' => $this->faker->email,
            ]);

            $browser->visit('/masuk')
                ->assertTitleContains('Masuk')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->click('@login-button')
                ->assertPathIs('/');
        });
    }
}
