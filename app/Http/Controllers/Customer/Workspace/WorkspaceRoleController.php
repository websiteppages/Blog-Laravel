<?php

namespace App\Http\Controllers\Customer\Workspace;

use App\Enums\WorkspacePermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkspaceRole\StoreRoleRequest;
use App\Http\Requests\WorkspaceRole\UpdateRoleRequest;
use App\Models\Workspace;
use App\Models\WorkspaceRole;
use App\Services\Customer\WorkspaceRoleService;
use Illuminate\Support\Facades\Auth;

class WorkspaceRoleController extends Controller
{
    public function __construct(private WorkspaceRoleService $WorkspaceRoleService) {}

    public function index(Workspace $workspace)
    {
        $this->authorize('manageRoles', $workspace);
        $roles = $workspace->roles()->withCount('members')->get();
        $allPermissions = WorkspacePermission::cases();
        return view('customer.workspace.roles.index', compact('workspace', 'roles', 'allPermissions'));
    }

    public function create(Workspace $workspace)
    {
        $this->authorize('manageRoles', $workspace);
        $allPermissions = WorkspacePermission::cases();
        // Group permissions by their prefix (workspace, posts, members, etc.)
        $grouped = collect($allPermissions)->groupBy(fn($p) => $p->group());
        return view('customer.workspace.roles.create', compact('workspace', 'grouped'));
    }

    public function store(StoreRoleRequest $request, Workspace $workspace)
    {
        $role = $this->WorkspaceRoleService->create($workspace, $request->validated(), $request->user());
        return redirect()->route('customer.workspaces.roles.index', $workspace)
            ->with('success', "Role \"{$role->name}\" created.");
    }

    public function edit(Workspace $workspace, WorkspaceRole $role)
    {
        $this->authorize('manageRoles', $workspace);
        $allPermissions = WorkspacePermission::cases();
        $grouped = collect($allPermissions)->groupBy(fn($p) => $p->group());
        return view('customer.workspace.roles.edit', compact('workspace', 'role', 'grouped'));
    }

    public function update(UpdateRoleRequest $request, Workspace $workspace, WorkspaceRole $role)
    {
        $this->WorkspaceRoleService->update($role, $request->validated(), $request->user());
        return redirect()->route('customer.workspaces.roles.index', $workspace)->with('success', 'Role updated.');
    }

    public function destroy(Workspace $workspace, WorkspaceRole $role)
    {
        $this->authorize('manageRoles', $workspace);
        $this->WorkspaceRoleService->delete($role, Auth::user());
        return redirect()->route('customer.workspaces.roles.index', $workspace)->with('success', 'Role deleted.');
    }
}
