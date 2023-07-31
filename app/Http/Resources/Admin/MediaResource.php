<?php

namespace App\Http\Resources\Admin;

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
            'file_type' => $this->file_type,
            'visibility' => $this->visibility,
            'total_views' => $this->views_count,
            'location' => $this->location(),
            'user' => UserResource::make($this->whenLoaded('user')),
            'created_at' => $this->created_at
        ];
    }

    private function location()
    {
        return "$this->state, $this->country";
    }
}
