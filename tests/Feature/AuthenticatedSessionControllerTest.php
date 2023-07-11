<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AuthenticatedSessionControllerTest extends TestCase
{
    use RefreshDatabase;


    public function testCreate()
    {
        $response = $this->get('/login');

        $response->assertStatus(200)
            ->assertInertia(function (Assert $page) {
                $page->component('Auth/Login')
                    ->has('canResetPassword')
                    ->has('status', null);
            });
    }

    public function testStore()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(RouteServiceProvider::HOME);

        $this->assertAuthenticated();
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');

        $this->assertGuest();
    }
}
