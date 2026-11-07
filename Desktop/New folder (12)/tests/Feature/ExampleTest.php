<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_root_redirects_guests_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/dashboard');
    }

    public function test_login_page_is_available_to_guests(): void
    {
        $this->get('/login')->assertOk()->assertSee('ورود به AutoParts Hub');
    }
}
