<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Unauthenticated');
        }

        /**
         * Resolve workspace safely
         */
        $workspace = $request->route('workspace');

        if (! $workspace) {
            $workspace = $user->currentWorkspace;
        }

        /**
         * If still no workspace → deny safely
         */
        if (! $workspace) {
            abort(403, 'Workspace not found');
        }

        /**
         * Permission check (centralized via user model or service)
         */
        if (! $user->hasPermission($permission, $workspace)) {

            if ($request->expectsJson()) {
                abort(403, 'Insufficient permissions');
            }

            return redirect()
                ->route('dashboard')   // ❗ safer than back()
                ->with('error', 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}
