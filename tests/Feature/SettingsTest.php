<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testStoreSettings()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $key = 'preferred_location';
        $value = 'US';

        $response = $this->postJson(route('settings.store'), [
            'key' => $key,
            'value' => $value,
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => [
                    'key' => $key,
                    'value' => $value,
                ],
                'message' => 'Successfully created',
            ]);

        $this->assertDatabaseHas('settings', [
            'key' => $key,
            'value' => $value,
            'user_id' => $user->id,
        ]);
    }
}
