<?php

namespace Tests\Feature;

use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SitemapTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
    }

    /**
     * Test sitemap fltered by category.
     */
    public function testCategory()
    {
        $category = Category::first();

        $response = $this->get(sprintf('/sitemap-%s.xml', $category->slug));

        if (empty($category)) {
            $response->assertStatus(404);
        } else {
            $response->assertStatus(200);
        }
    }
}
