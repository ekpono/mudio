<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminPagesController extends Controller
{
    public function dashboard()
    {
        return Inertia::render(
            'Admin/Users',
            [
                'summary' => [
                    ['name' => 'All Media', 'icon' => 'FolderIcon', 'amount' => Media::public()->count()],
                    ['name' => 'Video', 'icon' => 'VideoCameraIcon', 'amount' => Media::public()->video()->count()],
                    ['name' => 'Audio', 'icon' => 'MicrophoneIcon', 'amount' => Media::public()->audio()->count()],
                ],
            ]
        );
    }

    public function media()
    {
        return Inertia::render(
            'Admin/Media',
            [
                'summary' => [
                    ['name' => 'All Media', 'icon' => 'FolderIcon', 'amount' => Media::public()->count()],
                    ['name' => 'Video', 'icon' => 'VideoCameraIcon', 'amount' => Media::public()->video()->count()],
                    ['name' => 'Audio', 'icon' => 'MicrophoneIcon', 'amount' => Media::public()->audio()->count()],
                ],
            ]
        );
    }

    public function flag()
    {
        return Inertia::render('Admin/Report');
    }
}
