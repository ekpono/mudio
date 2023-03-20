<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Dashboard', [
            'summary' => [
                ['name' => 'All Media', 'icon' => 'FolderIcon', 'amount' => Media::public()->count()],
                ['name' => 'Video', 'icon' => 'VideoCameraIcon', 'amount' => Media::public()->video()->count()],
                ['name' => 'Audio', 'icon' => 'MicrophoneIcon', 'amount' => Media::public()->audio()->count()],
            ],
        ]);
    }

    public function uploads(Request $request)
    {
        return Inertia::render('Uploads', [
            'summary' => [
                ['name' => 'All Media', 'icon' => 'FolderIcon', 'amount' => Media::auth()->count()],
                ['name' => 'Video', 'icon' => 'VideoCameraIcon', 'amount' => Media::auth()->video()->count()],
                ['name' => 'Audio', 'icon' => 'MicrophoneIcon', 'amount' => Media::auth()->audio()->count()],
            ],
        ]);
    }
}
