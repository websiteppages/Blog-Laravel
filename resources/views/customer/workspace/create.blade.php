@extends('customer.layouts.app')
@section('title', 'Create Workspace')
@section('page-title', 'Create a Workspace')

@php $backUrl = route('customer.workspaces.index'); @endphp

@section('topbar-actions')

@endsection

@section('customer-content')
<div class="max-w-lg">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Create a Workspace</h1>
        <p class="text-gray-500 text-sm mt-1">A workspace is a shared environment for your team.</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-700 text-sm">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('customer.workspaces.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-lg font-medium text-gray-700 mb-1">Workspace Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                    placeholder="e.g. Acme Corp, My Blog, Dev Team"
                    class="w-full px-3 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm outline-none transition">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                    Create Workspace
                </button>
                <a href="{{ route('customer.workspaces.index') }}"
                    class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
