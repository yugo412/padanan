<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Term;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TermTest extends TestCase
{
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
    public function testSearch()
    {
        $response = $this->get('/cari?katakunci=x');

        $response->assertStatus(200);
    }

    /**
     * Test term detail.
     */
    public function testShow()
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
    public function testCategory()
    {
        $category = Category::first();
        $response = $this->get('/bidang/' . $category->slug);

        if (empty($category)) {
            $response->assertStatus(404);
        } else {
            $response->assertStatus(200);
        }
    }
}
