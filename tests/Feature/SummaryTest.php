<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SummaryTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testWeekly()
    {
        $response = $this->get('/ringkasan-mingguan');

        $response->assertStatus(200);
    }
}
