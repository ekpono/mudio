<?php

namespace Tests\Feature;

use App\Models\LikeAndDislike;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeAndDislikeTest extends TestCase
{
    use RefreshDatabase;

    public function testLikeableRelationship()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create();
        $like = LikeAndDislike::factory()->create([
            'user_id' => $user->id,
            'likeable_id' => $media->id,
            'likeable_type' => Media::class,
        ]);

        $likeable = $like->likeable;

        $this->assertInstanceOf(Media::class, $likeable);
        $this->assertEquals($media->id, $likeable->id);
    }

    public function testUserRelationship()
    {
        $user = User::factory()->create();
        $like = LikeAndDislike::factory()->create(['user_id' => $user->id]);

        $userRelation = $like->user;

        $this->assertInstanceOf(User::class, $userRelation);
        $this->assertEquals($user->id, $userRelation->id);
    }
}
