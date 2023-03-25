<?php

namespace App\Http\Resources;

use App\Models\MediaView;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'path' => $this->path,
            'views' => $this->computeVisits(),
            'date_created' => $this->created_at->diffForHumans(),
            'user_image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=60',
            'user_name' => $this->user->name,
            'poster' => $this->poster,
            'tags' => $this->cleanUpTags($this->tags),
            'visibility' => $this->visibility,
        ];
    }

    public function cleanUpTags($tags)
    {
        return collect($tags)->map(function ($item) {
            return $item['name'];
        })->toArray();
    }

    private function computeVisits()
    {
        return MediaView::where('media_id', $this->id)->count();
    }
}
