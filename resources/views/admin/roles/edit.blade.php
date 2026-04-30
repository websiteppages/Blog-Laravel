@extends('admin.layouts.app')

@section('title', 'Edit Role')
@section('page-title', 'Edit Role')

@php $backUrl = route('admin.roles.index'); @endphp

@section('page-badge')
<span class="status-pill status-published text-[10px]">
    {{ $role->users()->count() }} users
</span>
@endsection

@section('topbar-actions')
    <a href="{{ route('admin.roles.index') }}" class="btn-outline text-xs py-2 px-4">
        Cancel
    </a>
    <button form="role-form" type="submit" class="btn-primary text-xs py-2 px-4">
        Save Changes
    </button>
@endsection

@section('admin-content')

{{-- Protected role warning --}}
@if(in_array($role->name, ['owner']))
<div class="mb-5 p-4 rounded-xl bg-amber-pale border border-amber/30
             flex items-center gap-3 text-sm text-amber">
    <svg width="16" height="16" fill="none" stroke="currentColor"
         stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
    </svg>
    <span>
        <strong>Owner</strong> is a protected role.
        Permissions cannot be modified.
    </span>
</div>
@endif

@if($errors->any())
<div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200
             text-red-700 text-sm">
    <ul class="list-disc list-inside space-y-0.5">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form id="role-form" method="POST"
      action="{{ route('admin.roles.update', $role) }}">
@csrf
@method('PUT')

<div class="grid grid-cols-1 xl:grid-cols-[1fr_300px] gap-5 items-start">

    {{-- ── Permissions ──────────────────────────────────── --}}
    <div class="space-y-4">

        @foreach($groupedPermissions as $group => $permissions)
        @php
            $groupSlug = \Illuminate\Support\Str::slug($group);
            $groupChecked = collect($permissions)
                ->filter(fn($p) => in_array($p->value, $rolePermissionNames))
                ->count();
        @endphp
        <div class="bg-white border border-black/10 rounded-2xl overflow-hidden">

            <div class="flex items-center justify-between px-5 py-3
                         border-b border-black/8 bg-[#fafaf8]">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-bold text-ink">{{ $group }}</h3>
                    <span class="text-[10px] text-gray-400">
                        (<span id="count-{{ $groupSlug }}">{{ $groupChecked }}</span>/{{ count($permissions) }})
                    </span>
                </div>
                @unless(in_array($role->name, ['owner']))
                <button type="button"
                        onclick="toggleGroup('{{ $groupSlug }}')"
                        id="toggle-btn-{{ $groupSlug }}"
                        class="text-xs text-amber font-semibold hover:underline">
                    {{ $groupChecked === count($permissions) ? 'Deselect all' : 'Select all' }}
                </button>
                @endunless
            </div>

            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3"
                 data-group="{{ $groupSlug }}">

                @foreach($permissions as $permission)
                <label class="flex items-center gap-2.5 cursor-pointer group
                               p-2 rounded-lg hover:bg-cream-mid transition-colors
                               {{ in_array($role->name, ['owner']) ? 'opacity-60 cursor-not-allowed' : '' }}">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="{{ $permission->name }}"
                        class="perm-check w-4 h-4 rounded accent-amber cursor-pointer flex-shrink-0"
                        data-group="{{ $groupSlug }}"
                        onchange="updateGroupCount('{{ $groupSlug }}')"
                        {{ in_array($permission->name, $rolePermissionNames) ? 'checked' : '' }}
                        {{ in_array($role->name, ['owner']) ? 'disabled' : '' }}
                    >
                    <span class="text-sm text-gray-600 group-hover:text-ink
                                  transition-colors select-none leading-tight">
                        {{ ucwords(str_replace(['-'], ' ', $permission->name)) }}
                    </span>
                </label>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Sidebar ────────────────────────────────────────── --}}
    <div class="space-y-4">

        {{-- Role info --}}
        <div class="bg-white border border-black/10 rounded-2xl p-5">
            <h3 class="text-sm font-bold text-ink mb-4">Role Information</h3>

            {{-- Name (read-only for protected roles) --}}
            <div class="mb-4">
                <label class="form-label">Role Name</label>
                @if(in_array($role->name, ['owner', 'admin', 'reader']))
                <div class="form-input bg-[#f7f6f3] text-gray-500 cursor-not-allowed capitalize">
                    {{ $role->name }}
                </div>
                <p class="text-[11px] text-gray-400 mt-1">
                    Protected role — name cannot be changed
                </p>
                @else
                <input type="text"
                       name="display_name"
                       value="{{ old('display_name', $role->name) }}"
                       class="form-input capitalize"
                       readonly>
                <p class="text-[11px] text-gray-400 mt-1">
                    Role name cannot be changed after creation
                </p>
                @endif
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-[#f7f6f3] rounded-xl p-3 text-center">
                    <div class="font-display text-2xl font-bold text-ink">
                        {{ $role->users()->count() }}
                    </div>
                    <div class="text-[11px] text-gray-400 mt-0.5">
                        Users assigned
                    </div>
                </div>
                <div class="bg-[#f7f6f3] rounded-xl p-3 text-center">
                    <div class="font-display text-2xl font-bold text-amber"
                         id="total-selected">
                        {{ count($rolePermissionNames) }}
                    </div>
                    <div class="text-[11px] text-gray-400 mt-0.5">
                        Permissions
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick presets --}}
        @unless(in_array($role->name, ['owner']))
        <div class="bg-white border border-black/10 rounded-2xl p-5">
            <h3 class="text-sm font-bold text-ink mb-3">Quick Presets</h3>
            <div class="space-y-2">
                @foreach(['editor', 'author', 'moderator'] as $preset)
                <button type="button"
                        onclick="applyPreset('{{ $preset }}')"
                        class="w-full text-left text-sm px-3 py-2.5 rounded-xl
                               bg-[#f7f6f3] border border-black/8 text-gray-600
                               hover:border-amber hover:text-amber transition-colors
                               capitalize font-medium">
                    {{ ucfirst($preset) }} preset
                </button>
                @endforeach

                <button type="button"
                        onclick="clearAll()"
                        class="w-full text-left text-sm px-3 py-2.5 rounded-xl
                               border border-red-100 text-red-400
                               hover:border-red-300 hover:bg-red-50 transition-colors">
                    Clear all
                </button>
            </div>
        </div>
        @endunless

        {{-- Submit --}}
        @unless(in_array($role->name, ['owner']))
        <button type="submit" form="role-form"
                class="btn-primary w-full justify-center py-3 text-sm">
            Save Changes
        </button>
        @endunless
    </div>
</div>
</form>

@endsection

@push('scripts')
<script>
const presets = {
    editor: [
        'access dashboard','view posts','create posts','edit posts',
        'delete posts','publish posts','feature posts',
        'view categories','create categories','edit categories',
        'view tags','create tags','view comments','approve comments',
        'upload media','view reports',
    ],
    author: [
        'access dashboard','view posts','create posts','edit posts',
        'view categories','view tags','upload media',
    ],
    moderator: [
        'access dashboard','view posts','view comments',
        'approve comments','delete comments',
    ],
};

function updateGroupCount(group) {
    const checks  = document.querySelectorAll(`[data-group="${group}"].perm-check`);
    const checked = [...checks].filter(c => c.checked).length;
    const el      = document.getElementById(`count-${group}`);
    if (el) el.textContent = checked;
    updateToggleBtn(group, checks, checked);
    updateTotal();
}

function updateToggleBtn(group, checks, checked) {
    const btn = document.getElementById(`toggle-btn-${group}`);
    if (!btn) return;
    btn.textContent = checked === checks.length ? 'Deselect all' : 'Select all';
}

function updateTotal() {
    const total = document.querySelectorAll('.perm-check:checked').length;
    const el    = document.getElementById('total-selected');
    if (el) el.textContent = total;
}

function toggleGroup(group) {
    const checks     = document.querySelectorAll(`[data-group="${group}"].perm-check`);
    const allChecked = [...checks].every(c => c.checked);
    checks.forEach(c => c.checked = !allChecked);
    updateGroupCount(group);
}

function applyPreset(preset) {
    const perms = presets[preset] || [];
    document.querySelectorAll('.perm-check').forEach(c => c.checked = false);
    document.querySelectorAll('.perm-check').forEach(c => {
        if (perms.includes(c.value)) c.checked = true;
    });
    const groups = new Set(
        [...document.querySelectorAll('.perm-check')].map(c => c.dataset.group)
    );
    groups.forEach(g => { if (g) updateGroupCount(g); });
}

function clearAll() {
    document.querySelectorAll('.perm-check').forEach(c => c.checked = false);
    const groups = new Set(
        [...document.querySelectorAll('.perm-check')].map(c => c.dataset.group)
    );
    groups.forEach(g => { if (g) updateGroupCount(g); });
}

document.addEventListener('DOMContentLoaded', updateTotal);
</script>
@endpush
