<?php

namespace App\Http\Controllers;

class GuestController extends Controller
{
    public function __invoke()
    {
        return redirect(route('home'));
    }
}
