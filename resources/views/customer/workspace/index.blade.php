@extends('customer.layouts.app')

@section('title', 'My Workspaces')
@section('page-title', 'My Workspaces')

@section('topbar-actions')
    <div class=" flex flex-row items-center justify-center gap-4">
        @if(isset($currentWorkspace))
            @if($currentWorkspace->roles()->exists())
                <a href="{{ route('customer.workspaces.members', $currentWorkspace) }}" class="btn-primary text-sm py-2 px-4 {{ request()->routeIs('workspaces.members*') ? 'active' : '' }}">Members</a>
            @endif
            <a href="{{ route('customer.workspaces.roles.index', $currentWorkspace) }}" class="btn-primary text-sm py-2 px-4 {{ request()->routeIs('workspaces.roles*') ? 'active' : '' }}">Roles</a>

            <a href="{{ route('admin.view-analytics') }}" class="nav-link {{ request()->routeIs('admin.view-analytics') ? 'active' : '' }}">Analytics</a>
                    <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">Settings</a>

        @endif
        <a href="{{ route('customer.workspaces.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
            + New Workspace
        </a>
    </div>
@endsection

@section('customer-content')
<div class="space-y-6">

    {{-- Workspace Switcher --}}
                    @if(isset($currentWorkspace))
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm font-medium text-gray-700 transition">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            {{ $currentWorkspace->name }}
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute z-50 mt-1 w-64 bg-white rounded-xl shadow-lg border border-gray-100 py-1">
                            @foreach(auth()->user()->workspaces()->wherePivot('status',\App\Enums\MemberStatus::Active->value)->get()->merge(auth()->user()->ownedWorkspaces()->get())->unique('id') as $ws)
                            <form method="POST" action="{{ route('customer.workspaces.switch', $ws) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2.5 text-sm hover:bg-indigo-50 flex items-center gap-2 {{ $currentWorkspace->id === $ws->id ? 'text-indigo-600 font-semibold' : 'text-gray-700' }}">
                                    @if($currentWorkspace->id === $ws->id)
                                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    @else
                                        <span class="w-4"></span>
                                    @endif
                                    {{ $ws->name }}
                                </button>
                            </form>
                            @endforeach
                            <div class="border-t border-gray-100 mt-1 pt-1">
                                <a href="{{ route('customer.workspaces.create') }}" class="block px-4 py-2.5 text-sm text-indigo-600 hover:bg-indigo-50 font-medium">
                                    + New Workspace
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif


    @if($workspaces->isEmpty())
    <div class="bg-white rounded-xl border border-dashed border-gray-300 py-16 text-center">
        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        <p class="text-gray-500 font-medium">No workspaces yet</p>
        <p class="text-gray-400 text-sm mt-1">Create one to get started.</p>
    </div>
    @else
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($workspaces as $ws)
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:border-indigo-300 hover:shadow-sm transition group">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr($ws->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $ws->name }}</h3>
                        <p class="text-xs text-gray-400">{{ $ws->workspace_members_count ?? 0 }} members</p>
                    </div>
                </div>
                @if($ws->owner_id === auth()->id())
                <span class="text-xs bg-indigo-100 text-indigo-600 font-medium px-2 py-0.5 rounded-full">Owner</span>
                @endif
            </div>
            <div class="mt-4 flex items-center gap-2">
                <form method="POST" action="{{ route('customer.workspaces.switch', $ws) }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-medium rounded-lg transition">
                        Switch to workspace
                    </button>
                </form>
                @can('update', $ws)
                <a href="{{ route('customer.workspaces.edit', $ws) }}"
                    class="px-3 py-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 text-sm rounded-lg transition">
                    Edit
                </a>
                @endcan
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Simple Alpine.js-like toggle without needing Alpine
document.querySelectorAll('[x-data]').forEach(el => {
    const btn = el.querySelector('button');
    const dropdown = el.querySelector('[x-show]');
    if (btn && dropdown) {
        dropdown.style.display = 'none';
        btn.addEventListener('click', e => {
            e.stopPropagation();
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        });
        document.addEventListener('click', () => { dropdown.style.display = 'none'; });
    }
});
</script>
@endpush
