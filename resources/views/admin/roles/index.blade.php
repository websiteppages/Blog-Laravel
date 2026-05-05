@extends('admin.layouts.app')
@php
    use App\Enums\Permission as Perm;
@endphp


@section('title', 'Roles')
@section('page-title', 'Role Management')

@section('topbar-actions')
    @can(Perm::ViewUsers->value)
        <a href="{{ route('admin.users.index') }}" class="btn-primary text-sm py-2 px-4">
            Users
        </a>
    @endcan
    @can(Perm::CreateRoles->value)
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

            @can(Perm::EditRoles->value)
                @unless($role->isImmutable())
                    <a href="{{ route('admin.roles.edit', $role) }}"
                    class="w-8 h-8 rounded-lg border border-black/10 flex items-center justify-center text-gray-400 hover:text-ink transition">
                        ✎
                    </a>
                @endunless
            @endcan

            @can(Perm::DeleteRoles->value)
                @if(!$role->isProtected())
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



{{-- Table --}}
<div class="bg-white border border-black/10 rounded-2xl overflow-hidden mt-6">

    <div class="px-5 py-3 bg-[#fafaf8] border-b border-black/8 flex
                 items-center justify-between">
        <p class="text-xs text-gray-400">
            {{ $users->count() }} users total
        </p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        @if($user->avatar)
                        <img src="{{ $user->avatar_url }}"
                             alt="{{ $user->name }}"
                             class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                        @else
                        <div class="w-9 h-9 rounded-full bg-amber flex items-center
                                     justify-center text-sm font-bold text-white flex-shrink-0">
                            {{ $user->initials }}
                        </div>
                        @endif
                        <div>
                            <p class="text-sm font-semibold text-ink">{{ $user->name }}</p>
                            @if(!$user->email_verified_at)
                            <span class="text-[10px] text-orange-500 font-medium">Unverified</span>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="text-sm text-gray-500">{{ $user->email }}</td>
                <td>
                    @php $roleName = $user->getRoleNames()->first() ?? 'reader'; @endphp
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full
                                  text-[11px] font-semibold capitalize
                                  {{ match($roleName) {
                                      'owner'     => 'bg-amber-pale text-amber',
                                      default     => 'bg-blue-100 text-gray-600',
                                  } }}">
                        {{ \App\Enums\UserRole::tryFrom($roleName)?->label() ?? ucfirst($roleName) }}
                    </span>
                </td>

                <td class="text-sm text-gray-400">
                    {{ $user->created_at->format('M j, Y') }}
                </td>
                <td>
                    <div class="flex gap-1.5">
                        <a href="{{ route('admin.users.show', $user) }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium
                                border border-black/12 text-gray-500
                                hover:border-ink hover:text-ink transition-colors">
                            View
                        </a>

                        @can('update', $user)
                            <a href="{{ route('admin.users.edit', $user) }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold
                                    bg-ink text-cream hover:bg-ink/80 transition-colors">
                                Edit
                            </a>
                        @endcan


                        @can('removeUserRole', $user)
                            <form action="{{ route('admin.users.removeRole', $user) }}" method="POST"
                             onsubmit="return confirm('Remove Role {{ $user->name }}?')"
                             >
                                @csrf
                                <input type="hidden" name="role" value="{{$roleName}}">
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold
                                            border border-red-200 text-red-500
                                            hover:bg-red-50 transition-colors">Delete</button>
                            </form>
                        @endcan

                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-12 text-gray-400">
                    <div class="text-4xl mb-3">👤</div>
                    <p>No users found.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>










{{-- Role Access Matrix --}}
{{-- <div class="bg-white border border-black/10 rounded-2xl overflow-hidden mt-5">

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
                        <td class="text-left text-xl">
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

</div> --}}

@endsection
