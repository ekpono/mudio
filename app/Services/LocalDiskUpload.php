<?php

namespace App\Services;

use App\Models\Media;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use getID3;
use Illuminate\Support\Facades\Storage;

class LocalDiskUpload
{
    public string $source = 'local';

    public function handle($payload)
    {
        $file = $payload['file'];
        $title = $payload['title'];
        $visibility = $payload['visibility'];
        $description = $payload['description'];
        $tags = $payload['tags'];

        // Generate a unique name for the file
        $thumbnailPath = uniqid().'.jpg';

        // Store the file in local storage
        $filePathName = $file->storeAs('uploads', uniqid().'.'.$file->getClientOriginalExtension());

        $extension = $file->getClientOriginalExtension();
        $filename = $file->getClientOriginalName();

        $media = Media::create([
            'title' => $title,
            'file_name' => $filename,
            'path' => $filePathName,
            'extension' => $extension,
            'poster' => $thumbnailPath,
            'user_id' => auth()->id(),
            'source' => $this->source,
            'file_type' => $this->getType($extension),
            'visibility' => $visibility,
            'description' => $description,
            'uploaded_from_ip' => request()->ip(),
        ]);

        $filePath = $media->path;

        if (! empty($tags)) {
            $media->attachTags($tags);
        }

        if (in_array($extension, $this->videoExtensions())) {
            $this->createVideoThumbnail($filePath, $thumbnailPath);
        }

        if (in_array($extension, $this->audioExtensions())) {
            $path = storage_path().'/app/'.$filePathName;
            $this->crateAudioThumbnail($path, $thumbnailPath);
        }

        return [
            'filename' => $filename,
            'path' => $filePath,
        ];
    }

    private function audioExtensions()
    {
        return ['mp3', 'wav'];
    }

    private function videoExtensions()
    {
        return ['mp4'];
    }

    private function createVideoThumbnail($videoPath, $thumbnailPath)
    {
        $ffmpeg = FFMpeg::create([
            'timeout' => 0,
        ]);
        $video = $ffmpeg->open($videoPath);
        $frame = $video->frame(TimeCode::fromSeconds(1));
        $frame->save(storage_path($thumbnailPath));
    }

    public function crateAudioThumbnail($audioPath, $thumbnailPath)
    {
        $getID3 = new getID3();
        $audioInfo = $getID3->analyze($audioPath);

        if (isset($audioInfo['comments']['picture'][0]['data'])) {
            $thumbnail = $audioInfo['comments']['picture'][0]['data'];
            Storage::disk('public')->put($thumbnailPath, $thumbnail);
        }
    }

    public function getType($extension)
    {
        if (in_array($extension, $this->videoExtensions())) {
            return 'video';
        }

        return 'audio';
    }
}