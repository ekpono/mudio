<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class MediaLikeDislikeController extends Controller
{
    public function toggleLike(Media $media, Request $request)
    {
        $user = $request->user();
        $existingLike = $media->likesAndDislikes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            if ($existingLike->type === 'dislike') {
                $existingLike->update(['type' => 'like']);

                return response()->json(['message' => 'Media liked successfully.']);
            } else {
                $existingLike->delete();

                return response()->json(['message' => 'Media like removed successfully.']);
            }
        }

        $media->likesAndDislikes()->create(
            [
            'user_id' => $user->id,
            'type' => 'like',
            'likeable_id' => $media->id,
            'likeable_type' => Media::class,
            ]
        );

        return response()->json(['message' => 'Media liked successfully.']);
    }

    public function toggleDislike(Media $media, Request $request)
    {
        $user = $request->user();
        $existingDislike = $media->likesAndDislikes()->where('user_id', $user->id)->first();

        if ($existingDislike) {
            if ($existingDislike->type === 'like') {
                $existingDislike->update(['type' => 'dislike']);

                return response()->json(['message' => 'Media disliked successfully.']);
            } else {
                $existingDislike->delete();

                return response()->json(['message' => 'Media dislike removed successfully.']);
            }
        }

        $media->likesAndDislikes()->create(
            [
            'user_id' => $user->id,
            'type' => 'dislike',
            'likeable_id' => $media->id,
            'likeable_type' => Media::class,
            ]
        );

        return response()->json(['message' => 'Media disliked successfully.']);
    }
}
