<?php

namespace App\Services;

use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Torann\GeoIP\Facades\GeoIP;

class UserService {

    public function create(Request $request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'ip' => $request->ip(),
            'state_id' => $this->getStateViaIp(),
            'role' => $request->role ?? 'user'
        ]);
    }


    public function getStateViaIp()
    {
        $location = GeoIP::getLocation();

        $state = State::where('name', 'LIKE', "%$location->state_name%")->first();

        return $state->id;
    }

    public function update(Request $request)
    {
        //Update happens here
    }
}
