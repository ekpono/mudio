<?php

namespace App\Services;

use App\Http\Resources\MediaResource;
use App\Models\Media;

class MediaService
{
    public static function computeMostViewed()
    {
        return MediaResource::collection(Media::query()->inRandomOrder()->take(5)->get());
    }
}
