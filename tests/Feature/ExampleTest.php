<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_guest_users_are_redirected_from_private_member_routes_to_login(): void
    {
        $response = $this->get('/materi');

        $response->assertRedirect(route('login'));
    }
}
