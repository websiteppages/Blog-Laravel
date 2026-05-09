@extends('customer.layouts.app')
@section('title', 'Create Role')

@section('page-title', 'Create Role')

@php $backUrl = route('customer.workspaces.roles.index', $workspace); @endphp

@section('topbar-actions')

@endsection

@section('customer-content')
<div class="max-w-2xl">
    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-900 mt-2">Create Role</h1>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-700 text-sm">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('customer.workspaces.roles.store', $workspace) }}" class="space-y-5">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Role Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    placeholder="e.g. Editor, Moderator, Viewer"
                    class="w-full px-3 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>
                <x-customer.permissions-form :grouped="$grouped" />
            </div>

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                    Create Role
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
