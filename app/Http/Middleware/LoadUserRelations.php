<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoadUserRelations
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 🚀 Only load where needed (IMPORTANT OPTIMIZATION)
        if ($user && $request->routeIs('customer.*', 'admin.*')) {
            $user->loadMissing([
                'roles',
                'permissions',
            ]);
        }

        return $next($request);
    }
}
