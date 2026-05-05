@extends('admin.layouts.app')

@section('title', 'Assign Role')
@section('page-title', 'Assign Role')

@php $backUrl = route('admin.users.index'); @endphp

@section('admin-content')

<div class="max-w-xl mx-auto">

    {{-- User card --}}
    <div class="bg-white border border-black/10 rounded-2xl p-6 mb-5">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-amber flex items-center
                         justify-center text-lg font-bold text-white flex-shrink-0">
                {{ $user->initials }}
            </div>
            <div>
                <h2 class="font-display text-xl font-bold text-ink">
                    {{ $user->name }}
                </h2>
                <p class="text-sm text-gray-400">{{ $user->email }}</p>
                <div class="flex items-center gap-2 mt-1.5">
                    <span class="text-[11px] text-gray-400">Current role:</span>
                    @foreach($user->roles as $r)
                    <span class="text-[11px] font-bold bg-amber-pale text-amber
                                  px-2 py-0.5 rounded-full capitalize">
                        {{ $r->name }}
                    </span>
                    @endforeach
                    @if($user->roles->isEmpty())
                    <span class="text-[11px] text-gray-400">No role assigned</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Role selection --}}
    <div class="bg-white border border-black/10 rounded-2xl p-6">
        <h3 class="text-sm font-bold text-ink mb-5">Select New Role</h3>

        <form method="POST"
              action="{{ route('admin.users.assign-role.store', $user) }}">
        @csrf

        <div class="space-y-3 mb-6">
            @foreach($roles as $role)
            @php
                $isCurrent = in_array($role->name, $currentRoles);
                $isProtected = $role->name === \App\Enums\UserRole::Owner->value
                    && !auth()->user()->hasRole(\App\Enums\UserRole::Owner->value);
            @endphp
            <label class="flex items-center justify-between p-4 rounded-xl
                           border cursor-pointer transition-all
                           {{ $isCurrent
                               ? 'border-amber bg-amber-pale'
                               : 'border-black/10 hover:border-black/25' }}
                           {{ $isProtected ? 'opacity-50 cursor-not-allowed' : '' }}">

                <div class="flex items-center gap-3">
                    <input type="radio"
                           name="role"
                           value="{{ $role->name }}"
                           {{ $isCurrent ? 'checked' : '' }}
                           {{ $isProtected ? 'disabled' : '' }}
                           class="w-4 h-4 accent-amber">
                    <div>
                        <p class="text-sm font-semibold text-ink capitalize">
                            {{ $role->name }}
                            @if($isCurrent)
                            <span class="ml-1.5 text-[10px] bg-amber text-white
                                          px-2 py-0.5 rounded-full font-bold">
                                Current
                            </span>
                            @endif
                            @if($isProtected)
                            <span class="ml-1.5 text-[10px] bg-cream-mid text-gray-500
                                          px-2 py-0.5 rounded-full font-bold">
                                Owner only
                            </span>
                            @endif
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $role->permissions->count() }} permissions
                        </p>
                    </div>
                </div>

                {{-- Permission preview --}}
                <div class="flex flex-wrap gap-1 max-w-[200px] justify-end">
                    @foreach($role->permissions->take(4) as $perm)
                    <span class="text-[9px] bg-cream-mid text-gray-500
                                  px-1.5 py-0.5 rounded font-medium">
                        {{ $perm->name }}
                    </span>
                    @endforeach
                    @if($role->permissions->count() > 4)
                    <span class="text-[9px] text-gray-400">
                        +{{ $role->permissions->count() - 4 }} more
                    </span>
                    @endif
                </div>
            </label>
            @endforeach
        </div>

        @error('role')
        <p class="text-xs text-red-500 mb-3">{{ $message }}</p>
        @enderror

        <div class="flex gap-3">
            <button type="submit" class="btn-primary flex-1 justify-center py-2.5">
                Update Role
            </button>
            <a href="{{ route('admin.users.index') }}"
               class="btn-outline py-2.5 px-5">
                Cancel
            </a>
        </div>
        </form>
    </div>
</div>

@endsection
