<?php

namespace App\Services;

use App\Models\Media;
use App\Models\MediaView;

class MediaViewService
{
    public static function updateViews(Media $media)
    {
        $views = MediaView::updateOrcreate(
            ['media_id' => $media->id, 'user_id' => auth()->check() ? auth()->id() : null],
            [
                'headers' => json_encode([request()->headers->all()]),
                'ip_address' => request()->ip(),
                'is_auth' => auth()->check()
            ]);
        $views->increment('visits');
    }
}
