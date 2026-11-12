<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\AuthenticatesFeatureTests;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatesFeatureTests;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
