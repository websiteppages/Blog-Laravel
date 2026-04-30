@extends('admin.layouts.app')

@section('title', 'Create Role')
@section('page-title', 'Create New Role')

@php $backUrl = route('admin.roles.index'); @endphp

@section('topbar-actions')
    <a href="{{ route('admin.roles.index') }}" class="btn-outline text-xs py-2 px-4">
        Cancel
    </a>
    <button form="role-form" type="submit" class="btn-primary text-xs py-2 px-4">
        Create Role
    </button>
@endsection

@section('admin-content')

@if($errors->any())
<div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
    <p class="font-semibold mb-1">Please fix these errors:</p>
    <ul class="list-disc list-inside space-y-0.5">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form id="role-form" method="POST" action="{{ route('admin.roles.store') }}">
@csrf

<div class="grid grid-cols-1 xl:grid-cols-[1fr_300px] gap-5 items-start">

    {{-- ── Permissions ──────────────────────────────────── --}}
    <div class="space-y-4">

        @foreach($groupedPermissions as $group => $permissions)
        @php
            $groupSlug = \Illuminate\Support\Str::slug($group);
        @endphp
        <div class="bg-white border border-black/10 rounded-2xl overflow-hidden">

            {{-- Group header --}}
            <div class="flex items-center justify-between px-5 py-3
                         border-b border-black/8 bg-[#fafaf8]">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-bold text-ink">{{ $group }}</h3>
                    <span class="text-[10px] text-gray-400 font-medium">
                        (<span id="count-{{ $groupSlug }}">0</span>/{{ count($permissions) }})
                    </span>
                </div>
                <button type="button"
                        onclick="toggleGroup('{{ $groupSlug }}')"
                        id="toggle-btn-{{ $groupSlug }}"
                        class="text-xs text-amber font-semibold hover:underline">
                    Select all
                </button>
            </div>

            {{-- Permissions grid --}}
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3"
                 data-group="{{ $groupSlug }}">

                @foreach($permissions as $permission)
                <label class="flex items-center gap-2.5 cursor-pointer group
                               p-2 rounded-lg hover:bg-cream-mid transition-colors">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="{{ $permission->name }}"
                        class="perm-check w-4 h-4 rounded accent-amber cursor-pointer flex-shrink-0"
                        data-group="{{ $groupSlug }}"
                        onchange="updateGroupCount('{{ $groupSlug }}')"
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

        {{-- Role name --}}
        <div class="bg-white border border-black/10 rounded-2xl p-5">
            <h3 class="text-sm font-bold text-ink mb-4">Role Details</h3>

            <div>
                <label class="form-label">
                    Role Name <span class="text-red-400">*</span>
                </label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="form-input @error('name') error @enderror"
                       placeholder="e.g. developer, content_manager"
                       pattern="[a-z_]+"
                       required>
                @error('name')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-gray-400 mt-1.5">
                    Lowercase letters and underscores only
                </p>
            </div>
        </div>

        {{-- Selected count --}}
        <div class="bg-white border border-black/10 rounded-2xl p-5">
            <div class="text-center mb-4">
                <div class="font-display text-4xl font-bold text-amber"
                     id="total-selected">0</div>
                <div class="text-sm text-gray-400 mt-1">
                    permissions selected
                </div>
            </div>

            {{-- Quick presets --}}
            <div class="space-y-2">
                <p class="text-[11px] font-bold text-gray-400 uppercase
                            tracking-wider mb-2">
                    Quick Presets
                </p>

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

        {{-- Submit --}}
        <button type="submit" form="role-form"
                class="btn-primary w-full justify-center py-3 text-sm">
            Create Role
        </button>
    </div>
</div>
</form>

@endsection

@push('scripts')
<script>
// ── Preset permissions ─────────────────────────────────────────
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

// ── Update group count ────────────────────────────────────────
function updateGroupCount(group) {
    const checks  = document.querySelectorAll(`[data-group="${group}"].perm-check`);
    const checked = [...checks].filter(c => c.checked).length;
    const el      = document.getElementById(`count-${group}`);
    if (el) el.textContent = checked;
    updateTotal();
    updateToggleBtn(group, checks, checked);
}

function updateToggleBtn(group, checks, checked) {
    const btn = document.getElementById(`toggle-btn-${group}`);
    if (!btn) return;
    btn.textContent = checked === checks.length ? 'Deselect all' : 'Select all';
}

// ── Total selected ────────────────────────────────────────────
function updateTotal() {
    const total = document.querySelectorAll('.perm-check:checked').length;
    document.getElementById('total-selected').textContent = total;
}

// ── Toggle entire group ───────────────────────────────────────
function toggleGroup(group) {
    const checks   = document.querySelectorAll(`[data-group="${group}"].perm-check`);
    const allChecked = [...checks].every(c => c.checked);
    checks.forEach(c => c.checked = !allChecked);
    updateGroupCount(group);
}

// ── Apply preset ──────────────────────────────────────────────
function applyPreset(preset) {
    const perms = presets[preset] || [];

    // Clear all
    document.querySelectorAll('.perm-check').forEach(c => c.checked = false);

    // Check preset perms
    document.querySelectorAll('.perm-check').forEach(c => {
        if (perms.includes(c.value)) c.checked = true;
    });

    // Update all counts
    document.querySelectorAll('[data-group]').forEach(container => {
        const group = container.dataset.group;
        if (group) updateGroupCount(group);
    });
}

// ── Clear all ─────────────────────────────────────────────────
function clearAll() {
    document.querySelectorAll('.perm-check').forEach(c => c.checked = false);
    document.querySelectorAll('[data-group]').forEach(container => {
        const group = container.dataset.group;
        if (group) updateGroupCount(group);
    });
}

// ── Init counts ───────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const groups = new Set();
    document.querySelectorAll('.perm-check').forEach(c => {
        groups.add(c.dataset.group);
    });
    groups.forEach(g => { if(g) updateGroupCount(g); });
});
</script>
@endpush
