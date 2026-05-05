@extends('admin.layouts.app')

@section('title', 'Users')
@section('page-title', 'Users')

@section('topbar-actions')
    @can('create', \App\Models\User::class)
    <a href="{{ route('admin.users.create') }}"
       class="btn-primary text-xs py-2 px-4">
        + New User
    </a>
    @endcan
@endsection

@section('admin-content')

{{-- Filter bar --}}
<div class="flex flex-wrap gap-3 items-center mb-5">
    <form method="GET" class="flex gap-2 flex-1">
        <div class="flex items-center gap-2 bg-white border border-black/10
                     rounded-xl px-3 py-2 flex-1 max-w-xs">
            <svg width="13" height="13" fill="none" stroke="currentColor"
                 stroke-width="2" class="text-gray-400" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/>
                <path d="M21 21l-4.35-4.35"/>
            </svg>
            <input type="text" name="search"
                   value="{{ request('search') }}"
                   placeholder="Search users…"
                   class="text-sm bg-transparent border-none outline-none flex-1">
        </div>

        <select name="role" onchange="this.form.submit()"
                class="form-select text-sm py-2" style="width:auto">
            <option value="">All Roles</option>

            @foreach($allRoles as $role)
                <option value="{{ $role->name }}"
                        {{ request('role') === $role->name ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>

        @if(request()->hasAny(['search','role']))
        <a href="{{ route('admin.users.index') }}"
           class="btn-outline text-xs py-2 px-3">Clear</a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-white border border-black/10 rounded-2xl overflow-hidden">

    <div class="px-5 py-3 bg-[#fafaf8] border-b border-black/8 flex
                 items-center justify-between">
        <p class="text-xs text-gray-400">
            {{ $users->total() }} users total
        </p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Posts</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        @if($user->avatar)
                        <img src="{{ $user->avatar_url }}"
                             alt="{{ $user->name }}"
                             class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                        @else
                        <div class="w-9 h-9 rounded-full bg-amber flex items-center
                                     justify-center text-sm font-bold text-white flex-shrink-0">
                            {{ $user->initials }}
                        </div>
                        @endif
                        <div>
                            <p class="text-sm font-semibold text-ink">{{ $user->name }}</p>
                            @if(!$user->email_verified_at)
                            <span class="text-[10px] text-orange-500 font-medium">Unverified</span>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="text-sm text-gray-500">{{ $user->email }}</td>
                <td>
                    @php $roleName = $user->getRoleNames()->first() ?? 'reader'; @endphp
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full
                                  text-[11px] font-semibold capitalize
                                  {{ match($roleName) {
                                      'owner'     => 'bg-amber-pale text-amber',
                                      'admin'     => 'bg-red-50 text-red-700',
                                      'editor'    => 'bg-blue-50 text-blue-700',
                                      'author'    => 'bg-green-50 text-green-700',
                                      'moderator' => 'bg-purple-50 text-purple-700',
                                      default     => 'bg-gray-100 text-gray-600',
                                  } }}">
                        {{ \App\Enums\UserRole::tryFrom($roleName)?->label() ?? ucfirst($roleName) }}
                    </span>
                </td>
                <td>
                    <div class="flex items-center gap-1.5">
                        <span class="text-sm font-semibold text-ink">
                            {{ $user->posts_count }}
                        </span>
                        @if($user->published_posts_count > 0)
                        <span class="text-xs text-gray-400">
                            ({{ $user->published_posts_count }} pub.)
                        </span>
                        @endif
                    </div>
                </td>
                <td class="text-sm text-gray-400">
                    {{ $user->created_at->format('M j, Y') }}
                </td>
                <td>
                    <div class="flex gap-1.5">
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-medium
                                   border border-black/12 text-gray-500
                                   hover:border-ink hover:text-ink transition-colors">
                            View
                        </a>

                        @can('update', $user)
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-semibold
                                   bg-ink text-cream hover:bg-ink/80 transition-colors">
                            Edit
                        </a>
                        @endcan

                        @can('delete', $user)
                        <form method="POST"
                              action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('Delete {{ $user->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold
                                           border border-red-200 text-red-500
                                           hover:bg-red-50 transition-colors">
                                Delete
                            </button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-12 text-gray-400">
                    <div class="text-4xl mb-3">👤</div>
                    <p>No users found.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
    <div class="px-5 py-3 border-t border-black/8 bg-[#fafaf8]
                 flex items-center justify-between">
        <p class="text-xs text-gray-400">
            Showing {{ $users->firstItem() }}–{{ $users->lastItem() }}
            of {{ $users->total() }}
        </p>
        {{ $users->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection
