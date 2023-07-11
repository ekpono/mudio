<?php

namespace Tests\Feature;

use App\Models\FlagType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class FlagTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexFlagTypes()
    {
        $this->seed();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/media/flag/types');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                    ],
                ],
            ])
            ->assertJsonCount(11, 'data');

        $flagTypes = FlagType::pluck('name')->toArray();

        foreach ($flagTypes as $flagType) {
            $response->assertJsonFragment(['name' => $flagType]); // Assert that each flag type is included in the response
        }

        $this->assertDatabaseCount('flag_types', 11); // Assert the count of flag types in the database
    }

    public function testFlagsRelationship()
    {
        $this->seed();

        $flagType = FlagType::factory()->create();

        $this->assertInstanceOf(FlagType::class, $flagType);
    }
}
