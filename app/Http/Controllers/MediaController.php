<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\MediaRequest;
use App\Http\Requests\SearchMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use App\Services\LocalDiskUpload;
use App\Services\MediaService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(SearchMediaRequest $request)
    {
        $media = MediaService::getMediaQuery($request->query('query'));

        return response()->json(
            [
            'media' => MediaResource::collection($media->paginate(Helper::API_PER_PAGE)),
            'message' => 'Successfully fetched',
            ]
        );
    }

    public function myUploads(SearchMediaRequest $request)
    {
        $query = $request->query('query');
        $media = MediaResource::collection(
            Media::with(
                ['tags' => function ($query) {
                    $query->select('name');
                }]
            )
            ->where('title', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(Helper::API_PER_PAGE)
        );

        return response()->json(
            [
            'media' => $media,
            'message' => 'Successfully fetched',
            ]
        );
    }

    public function store(MediaRequest $request, LocalDiskUpload $diskUpload)
    {
        $payload = $request->validated();

        $response = $diskUpload->handle($payload);

        return response()->json($response);
    }

    public function update(MediaRequest $request, Media $media)
    {
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;
        $media->update($data);

        return response()->json(
            [
            'data' => $media,
            'message' => 'Successfully fetched',
            ]
        );
    }

    public function delete(Request $request, Media $media)
    {
        $authId = $request->user()->id;

        abort_if($media->user_id !== $authId, 401, 'Unauthorized');
        $media->update(
            [
            'deleted_by' => $authId,
            ]
        );
        $media->delete();

        return response()->json(
            [
            'message' => 'Delete successfully',
            ],
            202
        );
    }
}
