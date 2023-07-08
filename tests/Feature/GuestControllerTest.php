<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GuestControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestRedirectToHome()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
    }

}
