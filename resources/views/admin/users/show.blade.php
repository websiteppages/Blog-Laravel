@extends('admin.layouts.app')

@section('title', $user->name)
@section('page-title', $user->name)

@php $backUrl = route('admin.users.index'); @endphp

@section('topbar-actions')
    @can('update', $user)
    <a href="{{ route('admin.users.edit', $user) }}"
       class="btn-primary text-xs py-2 px-4">
        Edit User
    </a>
    @endcan
@endsection

@section('admin-content')

<div class="grid grid-cols-1 xl:grid-cols-[300px_1fr] gap-5">

    {{-- Profile card --}}
    <div class="space-y-4">
        <div class="bg-white border border-black/10 rounded-2xl p-6 text-center">
            @if($user->avatar)
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                 class="w-20 h-20 rounded-full object-cover mx-auto mb-4
                         ring-4 ring-cream-mid">
            @else
            <div class="w-20 h-20 rounded-full bg-amber flex items-center
                         justify-center text-2xl font-bold text-white mx-auto mb-4">
                {{ $user->initials }}
            </div>
            @endif

            <h2 class="font-display text-xl font-bold text-ink">{{ $user->name }}</h2>
            <p class="text-sm text-gray-400 mt-1">{{ $user->email }}</p>

            @php $roleName = $user->getRoleNames()->first() ?? 'reader'; @endphp
            <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold
                          capitalize bg-amber-pale text-amber">
                {{ \App\Enums\UserRole::tryFrom($roleName)?->label() ?? ucfirst($roleName) }}
            </span>

            @if($user->bio)
            <p class="text-sm text-gray-500 leading-relaxed mt-4 text-left">
                {{ $user->bio }}
            </p>
            @endif
        </div>

        {{-- Stats --}}
        <div class="bg-white border border-black/10 rounded-2xl p-5">
            <h3 class="text-sm font-bold text-ink mb-3">Statistics</h3>
            <div class="grid grid-cols-2 gap-3">
                @foreach([
                    ['📝', $user->posts_count, 'Total Posts'],
                    ['✅', $user->published_posts_count, 'Published'],
                    ['👁', format_number($user->posts()->sum('views_count')), 'Total Views'],
                    ['❤️', format_number($user->posts()->sum('likes_count')), 'Total Likes'],
                ] as [$icon, $val, $label])
                <div class="bg-[#f7f6f3] rounded-xl p-3 text-center">
                    <div class="text-base mb-0.5">{{ $icon }}</div>
                    <div class="font-display text-lg font-bold text-ink">{{ $val }}</div>
                    <div class="text-[10px] text-gray-400">{{ $label }}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Joined --}}
        <div class="bg-white border border-black/10 rounded-2xl p-5">
            <h3 class="text-sm font-bold text-ink mb-3">Account Info</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Joined</span>
                    <span class="font-medium">{{ $user->created_at->format('M j, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Email verified</span>
                    <span class="{{ $user->email_verified_at ? 'text-green-600' : 'text-red-500' }} font-medium">
                        {{ $user->email_verified_at ? 'Yes' : 'No' }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Last post</span>
                    <span class="font-medium">
                        {{ $user->posts()->latest()->first()?->created_at?->diffForHumans() ?? 'Never' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Posts --}}
    <div>
        <div class="bg-white border border-black/10 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-black/8">
                <h2 class="text-sm font-bold text-ink">
                    Posts by {{ $user->name }}
                </h2>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->posts()->with('category')->latest()->get() as $post)
                    <tr>
                        <td>
                            <div>
                                <p class="text-sm font-semibold text-ink line-clamp-1">
                                    {{ $post->title }}
                                </p>
                                @if($post->category)
                                <p class="text-xs text-gray-400">{{ $post->category->name }}</p>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="status-pill {{ $post->status->badgeClass() }}">
                                {{ $post->status->label() }}
                            </span>
                        </td>
                        <td class="text-sm font-medium">
                            {{ format_number($post->views_count) }}
                        </td>
                        <td class="text-xs text-gray-400">
                            {{ $post->created_at->format('M j, Y') }}
                        </td>
                        <td>
                            <div class="flex gap-1.5">
                                @can('update', $post)
                                <a href="{{ route('admin.posts.edit', $post) }}"
                                   class="px-3 py-1.5 rounded-lg text-xs font-semibold
                                           bg-ink text-cream">
                                    Edit
                                </a>
                                @endcan
                                <a href="{{ route('posts.show', $post->slug) }}"
                                   target="_blank"
                                   class="px-3 py-1.5 rounded-lg text-xs border
                                           border-black/12 text-gray-500 hover:border-ink">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-400 text-sm">
                            No posts yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
