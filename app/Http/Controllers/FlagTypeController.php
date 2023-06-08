<?php

namespace App\Http\Controllers;

use App\Http\Resources\FlagTypeResource;
use App\Models\FlagType;

class FlagTypeController extends Controller
{
    public function index()
    {
        return FlagTypeResource::collection(FlagType::all());
    }
}
