<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        // 🔥 cached settings (avoid DB hit)
        $settings = cache()->rememberForever('app_settings', function () {
            return Setting::pluck('value', 'key')->toArray();
        });

        $maintenance = $settings['maintenance_mode'] ?? '0';

        if ($maintenance === '1') {

            $user = Auth::user();

            $canBypass = $user
                && method_exists($user, 'canBypassMaintenance')
                && $user->canBypassMaintenance();

            // Routes allowed during maintenance
            $isAuthRoute = $request->routeIs([
                'login*',
                'logout',
                'register',
                'password.*',
                'fortify.*',
            ]);

            $isAdminRoute = $request->routeIs([
                'customer.*',
                'admin.*',
            ]);

            if (! $canBypass && ! $isAuthRoute && ! $isAdminRoute) {
                return response()->view('errors.maintenance', [
                    'message' => $settings['maintenance_message']
                        ?? "We're under maintenance",
                ], 503);
            }
        }

        return $next($request);
    }
}
