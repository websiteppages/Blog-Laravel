<?php

namespace App\Http\Middleware;

use App\Enums\MemberStatus;
use App\Models\Workspace;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensures the authenticated user has access to the workspace identified
 * by route parameter {workspace} or falls back to current_workspace_id.
 *
 * Design: We bind the workspace to the request so controllers
 * don't need to re-query it.
 */
class EnsureWorkspaceAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        // if (! $user) {
        //     return redirect()->route('login');
        // }

        // Allow route-level workspace param or fall back to user's current workspace
        $workspace = $request->route('workspace');

        if (! $workspace instanceof Workspace) {
            // Reload fresh from DB to get the latest current_workspace_id
            // (important immediately after an invite accept on the same request cycle)
            $workspace = $user->fresh()->currentWorkspace;
        }

        if (! $workspace) {
            return redirect()
                ->route('customer.workspaces.create')
                ->with(
                    'info',
                    'Please create or join a workspace to continue.'
                );
            //abort(404, 'Workspace not found.');
            //or
            //return redirect()->route('customer.workspaces.create')->with('info', 'Please create or join a workspace to continue.');
        }

        // Validate access: owner or active member
        // FIX: use MemberStatus::Active->value for the wherePivot comparison
        $hasAccess = $workspace->owner_id === $user->id
            || $user->workspaces()
                ->wherePivot('workspace_id', $workspace->id)
                ->wherePivot('status', MemberStatus::Active->value)
                ->exists();

        if (! $hasAccess) {
            abort(403, 'You do not have access to this workspace.');
        }

        // Share workspace with all views
        view()->share('currentWorkspace', $workspace);
        $request->merge(['_workspace' => $workspace]);

        return $next($request);
    }
}
