<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Models\Settings;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    public function store(SettingRequest $request)
    {
        $settings = Settings::updateOrcreate(
            ['key' => $request->get('key'), 'user_id' => auth()->id()],
            ['value' => $request->get('value')]
        );

        return response()->json([
            'data' => $settings,
            'message' => 'Successfully created'
        ], Response::HTTP_CREATED);
    }
}
