<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentReplyController extends Controller
{
    public function index(Comment $comment)
    {
        $perPage = 20;

        $comments = Comment::with('user')->where('commentable_type', Comment::class)
            ->where('parent_id', $comment->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return CommentResource::collection($comments);
    }

    public function store(Request $request, Comment $comment)
    {
        $request->validate(
            [
            'content' => 'required|string',
            ]
        );

        $reply = new Comment(
            [
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
            'commentable_type' => Comment::class,
            'commentable_id' => $comment->id,
            ]
        );

        $comment->replies()->save($reply);

        return CommentResource::make($reply);
    }
}
