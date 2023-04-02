<?php

namespace App\Services;

use App\Http\Resources\MediaResource;
use App\Models\Country;
use App\Models\Media;
use App\Models\MediaView;
use App\Models\Settings;
use Torann\GeoIP\Facades\GeoIP;

class MediaService
{
    public static function computeMostViewed()
    {
        return MediaResource::collection(Media::query()->inRandomOrder()->take(5)->get());
    }

    public static function getMediaQuery($query)
    {
        $mediaQuery = Media::query()->public()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', '%'.$query.'%')
                    ->orWhere('description', 'like', '%'.$query.'%');
            });

        $userWatchedMedia = MediaView::where('user_id', auth()->id())->pluck('media_id');
        $orderClause = '';

        if ($userWatchedMedia->isNotEmpty()) {
            $orderClause = ", CASE
                WHEN id IN (".implode(',', $userWatchedMedia->toArray()).") THEN 1
                ELSE 0
            END";
        }

        $preferredLocation = Settings::where('user_id', auth()->id())
            ->where('key', 'preferred_location')
            ->first();

        if ($preferredLocation) {
            $preferredCountry = Country::with('continent')->where('id', $preferredLocation->id)->first();

            return $mediaQuery->orderByRaw("
                CASE
                    WHEN country = '{$preferredCountry->name}' THEN 1
                    WHEN continent = '{$preferredCountry->continent->name}' THEN 2
                    ELSE 3
                END". $orderClause);
        }

        $userLocation = GeoIP::getLocation();

        return $mediaQuery->orderByRaw("
            CASE
                WHEN state = '{$userLocation['state']}' THEN 1
                WHEN country = '{$userLocation['country']}' THEN 2
                WHEN continent = '{$userLocation['continent']}' THEN 3
                ELSE 4
            END". $orderClause);
    }
}
