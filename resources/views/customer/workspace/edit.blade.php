@extends('customer.layouts.app')
@section('title', 'Edit Workspace')

@section('page-title', 'Edit Workspace')

@php $backUrl = route('customer.workspaces.index'); @endphp

@section('customer-content')
<div class="max-w-lg">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Edit Workspace</h1>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-6">
        @if($errors->any())
        <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-700 text-sm">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('customer.workspaces.update', $workspace) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Workspace Name</label>
                <input type="text" name="name" value="{{ old('name', $workspace->name) }}" required
                    class="w-full px-3 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm outline-none transition">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                    Save Changes
                </button>
            </div>
        </form>

        @can('delete', $workspace)
        <div class="border-t border-red-100 pt-5">
            <h3 class="text-sm font-semibold text-red-600 mb-2">Danger Zone</h3>
            <p class="text-xs text-gray-500 mb-3">Deleting a workspace permanently removes all posts, members, and settings.</p>
            <form method="POST" action="{{ route('customer.workspaces.destroy', $workspace) }}"
                onsubmit="return confirm('Are you sure? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition font-medium">
                    Delete Workspace
                </button>
            </form>
        </div>
        @endcan
    </div>
</div>
@endsection
