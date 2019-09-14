<?php

namespace Tests\Feature;

use App\Events\Word\SearchEvent;
use App\Events\Word\StoredEvent;
use App\Models\Category;
use App\Models\Term;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TermTest extends TestCase
{
    use WithFaker;

    public function testIndex(): void
    {
        $response = $this->get('/istilah');

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSearch(): void
    {
        Event::fake();

        $response = $this->get('/cari?e=abc');

        Event::assertDispatched(SearchEvent::class);

        $response->assertStatus(200);
    }

    /**
     * Test term not found when keyword is empty.
     */
    public function testSearchNotFound(): void
    {
        Event::fake();

        $response = $this->get('/cari?e=');

        Event::assertNotDispatched(SearchEvent::class);

        $response->assertStatus(200);
    }

    /**
     * Test term detail.
     */
    public function testShow(): void
    {
        $term = Term::first();

        $response = $this->get('/' . $term->slug);

        if (empty($term)) {
            $response->assertStatus(404);
        } else {
            $response->assertStatus(200);
        }
    }

    /**
     * Test show terms by category.
     */
    public function testCategory(): void
    {
        $category = Category::first();
        $response = $this->get('/bidang/' . $category->slug);

        if (empty($category)) {
            $response->assertStatus(404);
        } else {
            $response->assertStatus(200);
        }
    }

    public function testStore(): void
    {
        $term = factory(Term::class)->make([
            'category_id' => Category::inRandomOrder()->first()->id,
        ]);

        $response = $this->post('/tambah', $term->toArray());

        $response->assertStatus(302);
    }

    public function testStoreFromUser(): void
    {
        $this->actingAs($user = factory(User::class)->create());

        $response = $this->post('/tambah', $params = [
            'category_id' => Category::first()->id,
            'origin' => $this->faker->word,
            'locale' => $this->faker->word,
            'source' => $this->faker->url,
        ]);

        $response->assertRedirect('/tambah');
    }
}
