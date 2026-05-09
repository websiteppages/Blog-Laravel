@extends('customer.layouts.app')
@section('title', 'Members')
@section('page-title', 'Members')

@php $backUrl = route('customer.workspaces.index'); @endphp

@section('customer-content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Members</h1>
            <p class="text-gray-500 text-sm mt-1">Manage members of <strong>{{ $workspace->name }}</strong></p>
        </div>
    </div>

    {{-- Invite Form --}}
    @can('manageMembers', $workspace)
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Invite a Member</h2>
        @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-700 text-sm">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
        @endif
        <form method="POST" action="{{ route('customer.workspaces.members.invite', $workspace) }}" class="flex gap-3 flex-wrap">
            @csrf
            <input type="email" name="email" value="{{ old('email') }}" required
                placeholder="colleague@example.com"
                class="flex-1 min-w-[220px] px-3 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm outline-none transition">
            <select name="workspace_role_id" required
                class="px-3 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm outline-none transition bg-white">
                @foreach($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
            <button type="submit"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition">
                Send Invite
            </button>
        </form>
    </div>
    @endcan

    {{-- Active Members --}}
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Active Members ({{ $members->total() }})</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($members as $member)
            <div class="px-6 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm shrink-0">
                        {{ strtoupper(substr($member->user->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-medium text-gray-900 text-sm">{{ $member->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $member->user->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium
                        {{ $member->status->value === 'active' ? 'bg-green-100 text-green-700' :
                           ($member->status->value === 'suspended' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ $member->status->label() }}
                    </span>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full font-medium">
                        {{ $member->role?->name ?? '—' }}
                    </span>
                    @can('manageMembers', $workspace)
                    @if($member->user_id !== auth()->id() && $member->user_id !== $workspace->owner_id)
                    <div class="flex items-center gap-1">
                        {{-- Role change inline --}}
                        <form method="POST" action="{{ route('customer.workspaces.members.role', [$workspace, $member->user]) }}" class="flex items-center gap-1">
                            @csrf
                            @method('PUT')
                            <select name="workspace_role_id" onchange="this.form.submit()"
                                class="text-xs border border-gray-200 rounded px-2 py-1 bg-white focus:outline-none focus:border-indigo-400">
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $member->workspace_role_id === $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                                @endforeach
                            </select>
                        </form>
                        <form method="POST" action="{{ route('customer.workspaces.members.remove', [$workspace, $member->user]) }}"
                            onsubmit="return confirm('Remove this member?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 px-2 py-1 hover:bg-red-50 rounded transition">
                                Remove
                            </button>
                        </form>
                    </div>
                    @endif
                    @endcan
                </div>
            </div>
            @empty
            <div class="px-6 py-10 text-center text-gray-400 text-sm">No members yet.</div>
            @endforelse
        </div>
        @if($members->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $members->links() }}</div>
        @endif
    </div>

    {{-- Pending Invites --}}
    @if($invites->isNotEmpty())
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Pending Invites</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($invites as $invite)
            <div class="px-6 py-4 flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $invite->email }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Role: {{ $invite->role?->name }} ·
                        Invited by {{ $invite->inviter?->name }} ·
                        Expires {{ $invite->expires_at->diffForHumans() }}
                    </p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium
                        {{ $invite->status->value === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                           ($invite->status->value === 'accepted' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600') }}">
                        {{ ucfirst($invite->status->value) }}
                    </span>
                    @if($invite->isPending())
                        @can('manageMembers', $workspace)
                            <form method="POST" action="{{ route('customer.workspaces.invites.revoke', [$workspace, $invite]) }}"
                                onsubmit="return confirm('Revoke this invite?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 px-2 py-1 hover:bg-red-50 rounded transition">
                                    Revoke
                                </button>
                            </form>
                        @endcan
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @if($invites->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $invites->links() }}</div>
        @endif
    </div>
    @endif

</div>
@endsection
