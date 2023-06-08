<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFlagRequest;
use App\Models\Media;

class FlagController extends Controller
{
    public function store(StoreFlagRequest $request, Media $media)
    {
        $user = $request->user();

        $flag = $media->flags()->create([
            'user_id' => $user->id,
            'flag_id' => $request->input('flag_id'),
            'reason' => $request->input('reason'),
        ]);

        return response()->json(['message' => 'Media flagged successfully.', 'flag' => $flag], 201);
    }

    public function index(Media $media)
    {
        $flags = $media->flags()->with('user', 'flagType')->get();

        return response()->json(['flags' => $flags], 200);
    }
}
