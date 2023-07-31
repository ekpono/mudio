<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\FlagResource;
use App\Models\Flag;

class FlagController extends Controller
{
    public function index()
    {
        $flags = Flag::query()
            ->with(['user', 'media', 'flagType'])
            ->latest()
            ->paginate(Helper::API_PER_PAGE);

        return FlagResource::collection($flags);
    }
}
