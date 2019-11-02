<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        // WE need  an authenticated user
        if (! $user) {
            return response(['Unauthorised!'], 401);
        }

        // The user must be an admin
        if ($user->role != 'admin') {
            return response(['Forbidden', 'Insufficient privileges'], 403);
        }

        return $next($request);
    }
}
