@extends('customer.layouts.app')
@section('title', 'Roles & Permissions')

@section('page-title', 'Roles & Permissions')

@section('topbar-actions')
    @can('manageRoles', $workspace)
        <a href="{{ route('customer.workspaces.roles.create', $workspace) }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
            + New Role
        </a>
    @endcan

@endsection

@section('customer-content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Roles & Permissions</h1>
            <p class="text-gray-500 text-sm mt-1">Manage roles for <strong>{{ $workspace->name }}</strong></p>
        </div>

    </div>

    <div class="grid gap-4">
        @foreach($roles as $role)
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h3 class="font-semibold text-gray-900">{{ $role->name }}</h3>
                        @if($role->is_system)
                            <span class="text-xs bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded-full font-medium">System</span>
                        @endif
                        <span class="text-xs text-gray-400">{{ $role->members_count }} member{{ $role->members_count !== 1 ? 's' : '' }}</span>
                    </div>
                    <div class="flex flex-wrap gap-1.5 mt-3">
                        @forelse($role->permissions ?? [] as $permission)
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-mono">
                                 {{ \App\Enums\WorkspacePermission::labelFromValue($permission) }}
                            </span>
                        @empty
                            <span class="text-xs text-gray-400 italic">No permissions</span>
                        @endforelse
                    </div>
                </div>
                @can('manageRoles', $workspace)
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('customer.workspaces.roles.edit', [$workspace, $role]) }}"
                            class="text-sm text-indigo-600 hover:underline px-3 py-1.5 hover:bg-indigo-50 rounded-lg transition">Edit</a>
                        @if(!$role->is_system)
                            <form method="POST" action="{{ route('customer.workspaces.roles.destroy', [$workspace, $role]) }}"
                                onsubmit="return confirm('Delete this role? Members with this role must be reassigned first.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-500 hover:text-red-700 px-3 py-1.5 hover:bg-red-50 rounded-lg transition">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                @endcan
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
