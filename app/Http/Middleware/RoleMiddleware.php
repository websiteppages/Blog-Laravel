<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        // Login ஆகலன்னா
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        abort_unless(
            Auth::user()->hasAnyRole($roles),
            403,
            'Access Denied'
        );

        return $next($request);

        // Role check பண்றோம்
        // foreach ($roles as $role) {
        //     if (Auth::user()->hasRole($role)) {
        //         return $next($request);
        //     }
        // }

        // Role இல்லன்னா
        //abort(403, 'Access Denied — உங்களுக்கு permission இல்ல!');
    }
}
