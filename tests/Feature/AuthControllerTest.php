<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;
use Torann\GeoIP\Facades\GeoIP;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testRedirectToFacebook()
    {
        Socialite::shouldReceive('driver->redirect')->andReturn('mocked-redirect-url');

        $response = $this->get('/auth/facebook');

        $response->assertStatus(200);

        $response->assertSee('mocked-redirect-url');
    }

    public function testHandleFacebookCallback()
    {
        // Mock Socialite service to return a user with expected data
        $facebookUser = (object) [
            'getId' => 'facebook-user-id',
            'getEmail' => 'facebook@example.com',
            'getName' => 'Facebook User',
            'getAvatar' => 'https://example.com/avatar.jpg',
        ];

        Socialite::shouldReceive('facebook')->andReturn($facebookUser);

        // Mock GeoIP facade response
        GeoIP::shouldReceive('getLocation')->andReturn((object) [
            'state_name' => 'California',
        ]);

        // Mock the UserService to return a mocked state ID
        $mockedStateId = 1;
        $userServiceMock = $this->mock(UserService::class);
        $userServiceMock->shouldReceive('getStateViaIp')->andReturn($mockedStateId);

        // Access the handleFacebookCallback route
        $response = $this->get('/auth/facebook/callback');

        // Assert that the user is created and logged in
        $this->assertDatabaseHas('users', [
            'provider_id' => 'facebook-user-id',
            'email' => 'facebook@example.com',
            'name' => 'Facebook User',
            'profile_photo_path' => 'https://example.com/avatar.jpg',
            // ... Other user attributes
        ]);
        $this->assertTrue(auth()->check());

        // Assert that the response is a redirect
        $response->assertRedirect('home');

        dd('hi');
        // Add more assertions as needed based on your specific implementation
    }
}
