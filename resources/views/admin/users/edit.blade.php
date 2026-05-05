@extends('admin.layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit: ' . $user->name)

@php $backUrl = route('admin.users.index'); @endphp

@php
    use App\Enums\UserRole as Role;
@endphp

@section('topbar-actions')
    <button form="user-form" type="submit"
            class="btn-primary text-xs py-2 px-4">
        Save Changes
    </button>
@endsection

@section('admin-content')

<div class="max-w-xl">
<form id="user-form" method="POST"
      action="{{ route('admin.users.update', $user) }}"
      enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="bg-white border border-black/10 rounded-2xl p-6 space-y-4">

    {{-- Avatar preview --}}
    <div class="flex items-center gap-4">
        @if($user->avatar)
        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
             class="w-16 h-16 rounded-full object-cover ring-2 ring-cream-mid">
        @else
        <div class="w-16 h-16 rounded-full bg-amber flex items-center
                     justify-center text-xl font-bold text-white">
            {{ $user->initials }}
        </div>
        @endif
        <div>
            <label class="form-label mb-1">Avatar</label>
            <input type="file" name="avatar" accept="image/*"
                   class="form-input text-sm py-1.5">
        </div>
    </div>

    @if(auth()->user()->hasRole(Role::Owner->value))
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="form-label">Full Name *</label>
                <input name="name" type="text"
                    value="{{ old('name', $user->name) }}"
                    required class="form-input">
            </div>
            <div>
                <label class="form-label">Email *</label>
                <input name="email" type="email"
                    value="{{ old('email', $user->email) }}"
                    required class="form-input">
            </div>
            <div>
                <label class="form-label">
                    New Password
                    <span class="normal-case text-gray-400 font-normal">(optional)</span>
                </label>
                <input name="password" type="password"
                    class="form-input" placeholder="Leave blank to keep current">
            </div>
            <div>
                <label class="form-label">Confirm New Password</label>
                <input name="password_confirmation" type="password"
                    class="form-input">
            </div>
        </div>
    @endif

    {{-- Role — cannot change own role, cannot change owner --}}
    @if(auth()->id() !== $user->id && !$user->isImmutable())
        <div>
            <label class="form-label">Role</label>
            <select name="role" class="form-select">
                {{-- Enum roles --}}
                {{-- @foreach(\App\Enums\UserRole::cases() as $role)
                    @if(!in_array($role->value, [\App\Enums\UserRole::Owner->value]))
                        <option value="{{ $role->value }}"
                                {{ $user->hasRole($role->value) ? 'selected' : '' }}>
                            {{ $role->label() }}
                        </option>
                    @endif
                @endforeach --}}

                {{-- Custom roles --}}
                @foreach($customRoles as $role)
                    <option value="{{ $role->name }}">
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
        </div>
    @else
    <div class="p-3 bg-amber-pale rounded-xl text-sm text-amber">
        ⚠️ Role cannot be changed for this account.
    </div>
    @endif

    <div>
        <label class="form-label">Bio</label>
        <textarea name="bio" rows="3"
                  class="form-textarea resize-none">{{ old('bio', $user->bio) }}</textarea>
    </div>

    <button type="submit"
            class="btn-primary w-full justify-center py-2.5">
        Save Changes
    </button>
</div>
</form>
</div>

@endsection
