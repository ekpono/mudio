<?php

namespace App\Http\Controllers;

use App\Models\Media;

class TagController extends Controller
{
    public function index()
    {
        $userTags = Media::where('user_id', auth()->id())
            ->select('id')
            ->has('tags')
            ->with('tags:name')
            ->cursor()
            ->pluck('tags')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->values()
            ->toArray();

        return response()->json([
            'data' => $userTags,
            'message' => 'Successfully fetched'
        ]);
    }
}
