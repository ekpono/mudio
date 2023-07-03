<?php

namespace Tests\Feature;

use App\Models\Flag;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlaggingMediaTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testStoreFlag()
    {
        $media = Media::factory()->create();

        $user = User::factory()->create();

        $flag = Flag::factory()->create();

        $response = $this->actingAs($user)->post("/media/{$media->id}/flags", [
            'flag_id' => $flag->id,
            'reason' => 'This media violates the guidelines.',
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Media flagged successfully.']);

        $flag = $response->json('flag');

        $updatedMedia = Media::find($media->id);

        $this->assertTrue($updatedMedia->flags->contains('id', $flag['id']));
    }

    public function testIndexFlags()
    {
        $media = Media::factory()->create();

        $flags = Flag::factory()->count(3)->create(['media_id' => $media->id]);

        $response = $this->actingAs($this->user)->get("/media/{$media->id}/flags");

        $response->assertStatus(200);

        $responseFlags = $response->json('flags');

        $this->assertCount($flags->count(), $responseFlags);

        foreach ($flags as $flag) {
            $this->assertTrue(in_array($flag->id, array_column($responseFlags, 'id')));
        }
    }
}
