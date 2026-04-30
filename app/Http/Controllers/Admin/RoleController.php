<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Services\Admin\RoleService;
use App\Models\Role;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Role::class);

        return view('admin.roles.index', [
            'roles' => $this->roleService->getAll(),
            //'roleName' => Role::orderBy('name')->pluck('name'),
            'permissionGroups' => \App\Enums\Permission::grouped(),
            'accessMatrix' => $this->roleService->getAccessMatrix(), // optional
        ]);
    }

    public function create()
    {
        $this->authorize('create', Role::class);

        return view('admin.roles.create', [
            'groupedPermissions' => $this->roleService->getPermissionsGrouped(),
        ]);
    }

    public function store(StoreRoleRequest $request)
    {
        $this->authorize('create', Role::class);

        $role = $this->roleService->create($request->validated());

        return redirect()
            ->route('admin.roles.index')
            ->with('success',
                "Role \"{$role->name}\" created with "
                . count($request->permissions) . " permissions."
            );
    }

    public function edit(Role $role)
    {
        $this->authorize('update', $role);

        abort_if(
            $role->isImmutable(),
            403,
            "Role \"{$role->name}\" cannot be modified."
        );

        return view('admin.roles.edit', [
            'role'               => $role,
            'groupedPermissions' => $this->roleService->getPermissionsGrouped(),
            'rolePermissionNames'=> $this->roleService->getRolePermissions($role),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->authorize('update', $role);

        try {
            $this->roleService->update(
                $role,
                $request->validated()['permissions'] ?? []
            );
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', "Role \"{$role->name}\" permissions updated.");
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        try {
            $this->roleService->delete($role);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', "Role \"{$role->name}\" deleted.");
    }
}
