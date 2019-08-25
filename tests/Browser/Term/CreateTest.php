<?php

namespace Tests\Browser\Term;

use App\Models\Category;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateTest extends DuskTestCase
{
    use WithFaker;

    /**
     * @throws \Throwable
     */
    public function testForm(): void
    {
        $this->browse(function (Browser $browser) {
            $category = Category::inRandomOrder()
                ->whereIsPublished(true)
                ->first();

            $browser->loginAs(User::first())
                ->visit('/tambah')
                ->type('origin', $this->faker->word)
                ->type('locale', $this->faker->word)
                ->type('source', $this->faker->url)
                ->scrollTo('#action-buttons')
                ->click('@submit-button')
                ->assertSee('Halo');
        });
    }
}
