<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MediaCommentController extends Controller
{
    public function index(Media $media)
    {
        $perPage = 20;

        $comments = Comment::with('user')->where('commentable_type', Media::class)
            ->where('commentable_id', $media->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return CommentResource::collection($comments);
    }

    public function enableComments(Media $media)
    {
        $this->authorize('update', $media);

        $media->update(['comments_enabled' => true]);

        return response()->json(['message' => 'Comments enabled for the media.']);
    }

    public function disableComments(Media $media)
    {
        $this->authorize('update', $media);

        $media->update(['comments_enabled' => false]);

        return response()->json(['message' => 'Comments disabled for the media.']);
    }

    public function store(Request $request, Media $media)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = new Comment([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        $media->comments()->save($comment);

        return CommentResource::make($comment);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully.']);
    }
}
