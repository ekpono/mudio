<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CommentReplyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCommentReplyIndex()
    {
        $this->withoutExceptionHandling();
        $comment = Comment::factory()->create();

        // Create a few dummy reply comments
        Comment::factory()->create(['parent_id' => $comment->id, 'commentable_id' => $comment->id, 'commentable_type' => Comment::class]);
        Comment::factory()->create(['parent_id' => $comment->id, 'commentable_id' => $comment->id, 'commentable_type' => Comment::class]);

        $response = $this->get("/media/comments/{$comment->id}/reply");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'content',
                        'date',
                        'author',
                        'reply_count',
                        'reply_count',
                        'avatarSrc',
                    ],
                ],
            ]);
    }

    public function testCommentReplyStore()
    {
        $comment = Comment::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);

        $content = 'Reply to the comment.';
        $data = [
            'content' => $content,
        ];

        $response = $this->post("/media/comments/{$comment->id}/reply", $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'content',
                    'date',
                    'author',
                    'reply_count',
                    'reply_count',
                    'avatarSrc',
                ]
            ]);
    }
}
