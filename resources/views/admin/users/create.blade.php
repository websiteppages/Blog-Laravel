@extends('admin.layouts.app')

@section('title', 'Create User')
@section('page-title', 'Create User')

@php $backUrl = route('admin.users.index'); @endphp

@section('topbar-actions')
    <button form="user-form" type="submit" class="btn-primary text-xs py-2 px-4">
        Create User
    </button>
@endsection

@section('admin-content')

@if($errors->any())
<div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700">
    <ul class="list-disc list-inside space-y-0.5">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<div class="max-w-xl">
<form id="user-form" method="POST" action="{{ route('admin.users.store') }}"
      enctype="multipart/form-data">
@csrf

<div class="bg-white border border-black/10 rounded-2xl p-6 space-y-4">

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="form-label">Full Name *</label>
            <input name="name" type="text" value="{{ old('name') }}"
                   required class="form-input">
        </div>
        <div>
            <label class="form-label">Email *</label>
            <input name="email" type="email" value="{{ old('email') }}"
                   required class="form-input">
        </div>
        <div>
            <label class="form-label">Password *</label>
            <input name="password" type="password"
                   required class="form-input"
                   placeholder="Min. 8 characters">
        </div>
        <div>
            <label class="form-label">Confirm Password *</label>
            <input name="password_confirmation" type="password"
                   required class="form-input">
        </div>
    </div>

    <div>
        <label class="form-label">Role *</label>
        <select name="role" required class="form-select">
            <option value="">Select role…</option>
            @foreach(\App\Enums\UserRole::cases() as $role)
                {{-- Enum roles --}}
                {{-- @if(!in_array($role->value, [\App\Enums\UserRole::Owner->value]))
                    <option value="{{ $role->value }}"
                            {{ old('role') === $role->value ? 'selected' : '' }}>
                        {{ $role->label() }}
                    </option>
                @endif --}}
            @endforeach

            {{-- Custom roles --}}
            @foreach($customRoles as $role)
                <option value="{{ $role->name }}"
                    {{ old('role') === $role->name ? 'selected' : '' }}>
                    {{ ucfirst($role->name) }}
                </option>
            @endforeach


        </select>
    </div>

    <div>
        <label class="form-label">Bio</label>
        <textarea name="bio" rows="3"
                  class="form-textarea resize-none"
                  placeholder="Brief bio…">{{ old('bio') }}</textarea>
    </div>

    <div>
        <label class="form-label">Avatar</label>
        <input type="file" name="avatar" accept="image/*"
               class="form-input text-sm py-2">
    </div>

    <button type="submit"
            class="btn-primary w-full justify-center py-2.5">
        Create User
    </button>
</div>
</form>
</div>

@endsection
