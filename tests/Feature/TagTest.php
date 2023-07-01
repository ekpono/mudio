<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanGetTags()
    {
        $this->seed();

        $user = User::factory()->create();
        Media::factory()->create(['user_id' => $user->id])->attachTags(['tag1', 'tag2']);
        Media::factory()->create(['user_id' => $user->id])->attachTags(['tag1', 'tag3']);

        $response = $this->actingAs($user)->get('/tags');

        $response->assertStatus(200);
        // Assert the response data
        $response->assertJson([
            'data' => ['tag1', 'tag2', 'tag3'],
        ]);
    }
}
