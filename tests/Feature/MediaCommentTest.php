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
        // Assert the comment in the database
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
        // Create a comment using the factory
        $comment = Comment::factory()->create();

        $user = User::factory()->create();
        // Send a DELETE request to the comment deletion route
        $response = $this->actingAs($user)->delete("/media/comments/{$comment->id}");

        // Assert the response status code
        $response->assertStatus(200);

        // Assert that the comment no longer exists in the database
        $response->assertDontSee('comments', [
            'id' => $comment->id,
        ]);
    }
}
