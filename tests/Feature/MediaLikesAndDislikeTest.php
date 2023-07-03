<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaLikesAndDislikeTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testToggleLike()
    {
        $media = Media::factory()->create();

        $response = $this->actingAs($this->user)->post("/media/{$media->id}/like");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Media liked successfully.']);

        $updatedMedia = Media::find($media->id);

        $this->assertTrue($updatedMedia->likesAndDislikes->contains('type', 'like'));

        $this->assertFalse($updatedMedia->likesAndDislikes->contains('type', 'dislike'));
    }

    public function testToggleDislike()
    {
        $media = Media::factory()->create();

        $response = $this->actingAs($this->user)->post("/media/{$media->id}/dislike");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Media disliked successfully.']);

        $updatedMedia = Media::find($media->id);

        $this->assertTrue($updatedMedia->likesAndDislikes->contains('type', 'dislike'));

        $this->assertFalse($updatedMedia->likesAndDislikes->contains('type', 'like'));
    }

    public function testToggleLikeWhenAlreadyLiked()
    {
        $media = Media::factory()->create();

        $user = User::factory()->create();

        $media->likesAndDislikes()->create([
            'user_id' => $user->id,
            'type' => 'like',
            'likeable_id' => $media->id,
            'likeable_type' => Media::class,
        ]);

        $response = $this->actingAs($user)->post("/media/{$media->id}/like");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Media like removed successfully.']);

        $updatedMedia = Media::find($media->id);

        $this->assertFalse($updatedMedia->likesAndDislikes->contains('type', 'like'));
    }

    public function testToggleDislikeWhenAlreadyDisliked()
    {
        $media = Media::factory()->create();

        $user = User::factory()->create();

        $media->likesAndDislikes()->create([
            'user_id' => $user->id,
            'type' => 'dislike',
            'likeable_id' => $media->id,
            'likeable_type' => Media::class,
        ]);

        $response = $this->actingAs($this->user)->post("/media/{$media->id}/dislike");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Media disliked successfully.']);

        $updatedMedia = Media::find($media->id);

        $this->assertTrue($updatedMedia->likesAndDislikes->contains('type', 'dislike'));
    }
}
