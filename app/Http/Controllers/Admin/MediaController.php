<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\MediaResource;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $media = Media::query()
            ->with('user')
            ->withCount('views')
            ->when($request->has('query'), function($query) use($request) {
                $search = $request->query('query');
                $query->where('title', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%");
            })
            ->latest()
            ->paginate(Helper::API_PER_PAGE);

        return MediaResource::collection($media);
    }
}
