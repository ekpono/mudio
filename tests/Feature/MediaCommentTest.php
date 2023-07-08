<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaCommentTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanCreateCommentForMedia()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post('/media/'.$media->id.'/comments', [
            'content' => 'Test comment',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('comments', [
            'commentable_type' => Media::class,
            'commentable_id' => $media->id,
            'content' => 'Test comment',
        ]);
    }

    public function testCreateComment()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create();

        $commentData = [
            'user_id' => $user->id,
            'commentable_id' => $media->id,
            'commentable_type' => Media::class,
            'parent_id' => null,
            'content' => 'Test comment',
        ];

        $comment = Comment::factory()->create($commentData);

        $this->assertEquals($commentData['user_id'], $comment->user_id);
        $this->assertEquals($commentData['commentable_id'], $comment->commentable_id);
        $this->assertEquals($commentData['commentable_type'], $comment->commentable_type);
        $this->assertEquals($commentData['parent_id'], $comment->parent_id);
        $this->assertEquals($commentData['content'], $comment->content);
    }

    public function testDeleteComment()
    {
        $comment = Comment::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete("/media/comments/{$comment->id}");

        $response->assertStatus(200);

        $response->assertDontSee('comments', [
            'id' => $comment->id,
        ]);
    }
}
