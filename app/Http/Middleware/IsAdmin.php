<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ($user->role->value === Role::ADMIN->value || $user->role === Role::SUPER_ADMIN->value)) {
            return $next($request);
        }


        //Confuse them with 404 instead of 403
        abort(403, 'Not Found');
    }
}
