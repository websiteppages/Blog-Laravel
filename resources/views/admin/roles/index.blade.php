@extends('admin.layouts.app')

@section('title', 'Roles')
@section('page-title', 'Role Management')

@section('topbar-actions')
    @can('create roles')
        <a href="{{ route('admin.roles.create') }}" class="btn-primary text-sm py-2 px-4">
            + New Role
        </a>
    @endcan
@endsection

@section('admin-content')

{{-- Flash Messages --}}
@if(session('success'))
<div class="mb-5 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm flex items-center gap-2">
    <span>✓</span> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-5 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
    {{ session('error') }}
</div>
@endif

{{-- Roles Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

@foreach($roles as $role)
<div class="bg-white border border-black/10 rounded-2xl p-5">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-4">

        <div>
            <div class="flex items-center gap-2 mb-1">

                <span class="font-display text-lg font-bold text-ink capitalize">
                    {{ $role->name }}
                </span>

                {{-- Protected Badge --}}
                @if($role->isProtected())
                    <span class="text-[10px] font-semibold bg-cream-mid text-gray-500 px-2 py-0.5 rounded-full">
                        Protected
                    </span>
                @endif

            </div>

            <p class="text-xs text-gray-400">
                {{ $role->users_count }} {{ Str::plural('user', $role->users_count) }} assigned
            </p>
        </div>

        {{-- Actions --}}
        <div class="flex gap-1">

            @can('edit roles')
            @unless($role->isImmutable())
                <a href="{{ route('admin.roles.edit', $role) }}"
                   class="w-8 h-8 rounded-lg border border-black/10 flex items-center justify-center text-gray-400 hover:text-ink transition">
                    ✎
                </a>
            @endunless
            @endcan

            @can('delete roles')
            @if(!$role->isProtected() && !$role->hasUsers())
                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}"
                      onsubmit="return confirm('Delete {{ $role->name }} role?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="w-8 h-8 rounded-lg border border-black/10 flex items-center justify-center text-gray-400 hover:text-red-500 transition">
                        🗑
                    </button>
                </form>
            @endif
            @endcan

        </div>
    </div>

    {{-- Permissions by Group --}}
    <div class="space-y-2 mb-4">

        @foreach($permissionGroups as $groupName => $permissions)

            @php
                $groupValues = collect($permissions)->map->value;
                $rolePermissions = $role->permissions->pluck('name');
                $count = $rolePermissions->intersect($groupValues)->count();
                $total = count($permissions);
            @endphp

            @if($count > 0)
            <div class="flex items-center justify-between">

                <span class="text-xs text-gray-500">
                    {{ $groupName }}
                </span>

                <div class="flex items-center gap-2">

                    <div class="w-16 h-1 bg-cream-mid rounded-full overflow-hidden">
                        <div class="h-1 bg-amber rounded-full"
                             style="width: {{ ($count / $total) * 100 }}%">
                        </div>
                    </div>

                    <span class="text-[11px] text-gray-400">
                        {{ $count }}/{{ $total }}
                    </span>

                </div>
            </div>
            @endif

        @endforeach
    </div>

    {{-- Footer --}}
    <div class="pt-3 border-t border-black/8 flex justify-between items-center">

        <span class="text-xs text-gray-400">Total permissions</span>

        <span class="text-sm font-bold text-ink">
            {{ $role->permissions->count() }}
        </span>

    </div>

</div>
@endforeach

</div>

{{-- Role Access Matrix --}}
<div class="bg-white border border-black/10 rounded-2xl overflow-hidden mt-5">

    <div class="px-5 py-4 border-b border-black/8 bg-[#fafaf8]">
        <h2 class="text-sm font-bold text-ink">Role Access Matrix</h2>
        <p class="text-xs text-gray-400 mt-0.5">
            What each role can do in the system
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="data-table text-center">

            <thead>
                <tr>
                    <th class="text-left">Capability</th>
                    @foreach($roles as $roleName)
                        <th class="capitalize">{{ $roleName->name }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($accessMatrix as $row)
                <tr>
                    <td class="text-left text-sm font-medium text-gray-700">
                        {{ $row['capability'] }}
                    </td>

                    @foreach($row['access'] as $has)
                        <td>
                            @if($has)
                                <span class="text-green-500">✓</span>
                            @else
                                <span class="text-gray-200">—</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>

@endsection
