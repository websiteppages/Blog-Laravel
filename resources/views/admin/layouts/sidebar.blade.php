<aside class="admin-sidebar" id="admin-sidebar">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <a href="{{ route('admin.overview') }}" class="sidebar-logo-brand">
                <div class="sidebar-logo-icon">I</div>
                <div class="sidebar-logo-text">
                    <span class="sidebar-logo-name">Inkwell</span>
                    <span class="sidebar-logo-sub">Admin Panel</span>
                </div>
            </a>

            {{-- Mobile close button --}}
            <button onclick="closeSidebar()"
                    class="sidebar-logout-btn lg:hidden">
                <svg width="16" height="16" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>


        {{-- Navigation --}}
<nav class="sidebar-nav">

    {{-- Overview --}}
    <span class="sidebar-section-label">Overview</span>

     <a href="{{ route('admin.overview') }}"
       class="sidebar-item {{ request()->routeIs('admin.overview') ? 'active' : '' }}">
        <svg width="15" height="15" fill="none" stroke="currentColor"
             stroke-width="1.8" viewBox="0 0 24 24">
            <rect x="3" y="3" width="7" height="7" rx="1.5"/>
            <rect x="14" y="3" width="7" height="7" rx="1.5"/>
            <rect x="14" y="14" width="7" height="7" rx="1.5"/>
            <rect x="3" y="14" width="7" height="7" rx="1.5"/>
        </svg>
        Overview
    </a>


    <a href="{{ route('customer.dashboard') }}"
       class="sidebar-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
        <svg width="15" height="15" fill="none" stroke="currentColor"
             stroke-width="1.8" viewBox="0 0 24 24">
            <rect x="3" y="3" width="7" height="7" rx="1.5"/>
            <rect x="14" y="3" width="7" height="7" rx="1.5"/>
            <rect x="14" y="14" width="7" height="7" rx="1.5"/>
            <rect x="3" y="14" width="7" height="7" rx="1.5"/>
        </svg>
        Dashboard
    </a>

    {{-- @can('view roles') --}}
    <a href="{{ route('admin.roles.index') }}"
       class="sidebar-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
        <svg width="15" height="15" fill="none" stroke="currentColor"
             stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
        </svg>
        Roles & Permissions
    </a>
    {{-- @endcan --}}



    {{--  @can('view analytics')
    <a href="{{ route('admin.analytics') }}"
       class="sidebar-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
        <svg width="15" height="15" fill="none" stroke="currentColor"
             stroke-width="1.8" viewBox="0 0 24 24">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
        </svg>
        Analytics
    </a>
    @endcan --}}

    {{-- Content --}}
    {{-- <span class="sidebar-section-label">Content</span>

    @can('view posts')
    @php $totalPosts = \App\Models\Post::when(
        !auth()->user()->hasPermissionTo('edit any post'),
        fn($q) => $q->where('user_id', auth()->id())
    )->count(); @endphp
    <a href="{{ route('admin.posts.index') }}"
       class="sidebar-item {{ request()->routeIs('admin.posts.index') ? 'active' : '' }}">
        <svg width="15" height="15" fill="none" stroke="currentColor"
             stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
        </svg>
        My Posts
        <span class="sidebar-badge sidebar-badge-gray">{{ $totalPosts }}</span>
    </a>
    @endcan

    @can('create posts')
    <a href="{{ route('admin.posts.create') }}"
       class="sidebar-item {{ request()->routeIs('admin.posts.create') ? 'active' : '' }}">
        <svg width="15" height="15" fill="none" stroke="currentColor"
             stroke-width="1.8" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        New Post
    </a>
    @endcan

    @can('view categories')
    <a href="{{ route('admin.categories.index') }}"
       class="sidebar-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <svg width="15" height="15" fill="none" stroke="currentColor"
             stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
        </svg>
        Categories
    </a>
    @endcan

    @can('view tags')
    <a href="{{ route('admin.tags.index') }}"
       class="sidebar-item {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
        <svg width="15" height="15" fill="none" stroke="currentColor"
             stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
            <line x1="7" y1="7" x2="7.01" y2="7"/>
        </svg>
        Tags
    </a>
    @endcan --}}

    {{-- @can('view comments')
    @php
        $pendingComments = \App\Models\Comment::where('is_approved', false)->count();
    @endphp
    <a href="{{ route('admin.comments.index') }}"
       class="sidebar-item {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
        <svg width="15" height="15" fill="none" stroke="currentColor"
             stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
        Comments
        @if($pendingComments > 0)
        <span class="sidebar-badge sidebar-badge-red">{{ $pendingComments }}</span>
        @endif
    </a>
    @endcan --}}

    {{-- @can('view reports')
    @php $pendingReports = \App\Models\PostReport::where('status', 'pending')->count(); @endphp
    <a href="{{ route('admin.reports.index') }}"
       class="sidebar-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <svg width="15" height="15" fill="none" stroke="currentColor"
             stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
            <line x1="4" y1="22" x2="4" y2="15"/>
        </svg>
        Reports
        @if($pendingReports > 0)
        <span class="sidebar-badge sidebar-badge-red">{{ $pendingReports }}</span>
        @endif
    </a>
    @endcan --}}

    {{-- Management — admins only ──────────────────────── --}}
    {{-- @canany(['view users', 'view roles'])
    <span class="sidebar-section-label">Management</span>
    @endcanany

    @can('view users')
    @php $totalUsers = \App\Models\User::count(); @endphp
    <a href="{{ route('admin.users.index') }}"
       class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <svg width="15" height="15" fill="none" stroke="currentColor"
             stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
        Users
        <span class="sidebar-badge sidebar-badge-gray">{{ $totalUsers }}</span>
    </a>
    @endcan --}}



    {{-- System ────────────────────────────────────────── --}}
    {{-- <span class="sidebar-section-label">System</span>

    <a href="{{ route('home') }}" target="_blank" class="sidebar-item">
        <svg width="15" height="15" fill="none" stroke="currentColor"
             stroke-width="1.8" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/>
            <line x1="2" y1="12" x2="22" y2="12"/>
            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
        </svg>
        View Site
        <svg width="10" height="10" fill="none" stroke="currentColor"
             stroke-width="2" viewBox="0 0 24 24"
             style="margin-left:auto;opacity:0.3;flex-shrink:0">
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
            <polyline points="15 3 21 3 21 9"/>
            <line x1="10" y1="14" x2="21" y2="3"/>
        </svg>
    </a>

    @can('view settings')
    <a href="{{ route('admin.settings') }}"
       class="sidebar-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
        <svg width="15" height="15" fill="none" stroke="currentColor"
             stroke-width="1.8" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="3"/>
            <path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/>
        </svg>
        Settings
    </a>
    @endcan --}}
</nav>

  {{-- User card --}}
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
            </div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">
                    {{ auth()->user()->name ?? 'User' }}
                </div>
                <div class="sidebar-user-role">
                    {{ auth()->user()->getRoleNames()->first() ?? 'member' }}
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout-btn" title="Sign out">
                    <svg width="14" height="14" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </button>
            </form>
        </div>


        {{-- Footer — Notification link --}}
        {{-- <div class="sidebar-footer">
            @php
                $unreadNotifs = auth()->user()->unreadNotifications()->count();
            @endphp
            <a href="{{ route('notifications.index') }}"
               class="sidebar-notif-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor"
                     stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <span>Notifications</span>
                @if($unreadNotifs > 0)
                <span class="sidebar-badge sidebar-badge-amber" style="margin-left:auto">
                    {{ $unreadNotifs > 99 ? '99+' : $unreadNotifs }}
                </span>
                @endif
            </a>
        </div> --}}

    </aside>

