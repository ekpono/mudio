<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class CountryControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testGetCountries()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/countries');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Successfully fetched',
            ]);

        $countries = Country::all();

        $response->assertJsonFragment(['data' => $countries->toArray()]);
    }
}
