@extends('customer.layouts.app')
@section('title', 'Edit Role')

@section('page-title', 'Edit Role')

@php $backUrl = route('customer.workspaces.roles.index', $workspace); @endphp

@section('topbar-actions')

@endsection

@section('customer-content')
<div class="max-w-2xl">
    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-900 mt-2">Edit Role: {{ $role->name }}</h1>
        @if($role->is_system)
        <p class="text-sm text-amber-600 mt-1 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            System role — name cannot be changed, only permissions.
        </p>
        @endif
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-700 text-sm">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('customer.workspaces.roles.update', [$workspace, $role]) }}" class="space-y-5">
            @csrf
            @method('PUT')

            @if(!$role->is_system)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role Name</label>
                <input type="text" name="name" value="{{ old('name', $role->name) }}" required
                    class="w-full px-3 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm outline-none transition">
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>
                <x-customer.permissions-form :grouped="$grouped" :role="$role" />
            </div>

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                    Save Changes
                </button>
                <a href="{{ route('customer.workspaces.roles.index', $workspace) }}"
                    class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection


@push('scripts')
<script>
    // ── Toggle entire group ───────────────────────────────────────
    function toggleGroup(group) {
        const checks   = document.querySelectorAll(`[data-group="${group}"].perm-check`);
        const allChecked = [...checks].every(c => c.checked);
        checks.forEach(c => c.checked = !allChecked);
        updateGroupCount(group);
    }
    // ── Update group count ────────────────────────────────────────
    function updateGroupCount(group) {
        const checks  = document.querySelectorAll(`[data-group="${group}"].perm-check`);
        const checked = [...checks].filter(c => c.checked).length;
        const el      = document.getElementById(`count-${group}`);
        if (el) el.textContent = checked;
        updateTotal();
        updateToggleBtn(group, checks, checked);
    }
</script>
@endpush
