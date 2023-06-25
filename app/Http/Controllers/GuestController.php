<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class GuestController extends Controller
{
    public function __invoke()
    {
        return redirect(route('home'));
    }
}
