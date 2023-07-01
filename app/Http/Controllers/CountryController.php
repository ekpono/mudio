<?php

namespace App\Http\Controllers;

use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Country::all(),
            'message' => 'Successfully fetched',
        ]);
    }
}
