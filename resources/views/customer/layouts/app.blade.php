@extends('layouts.base')

@section('title', 'Customer Page')

@push('styles')
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ══════════════════════════════════════════════
           BASE
        ══════════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            background: #f7f6f3;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 14px;
            color: #0d0d0d;
            -webkit-font-smoothing: antialiased;
        }

        /* ══════════════════════════════════════════════
           LAYOUT
        ══════════════════════════════════════════════ */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ══════════════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════════════ */
        .admin-sidebar {
            width: 256px;
            flex-shrink: 0;
            background: #0d0d0d;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: none;
            transition: transform 0.3s ease;
            z-index: 100;
        }
        .admin-sidebar::-webkit-scrollbar { display: none; }

        /* ── Sidebar Logo ──────────────────────────── */
        .sidebar-logo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 16px 14px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .sidebar-logo-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .sidebar-logo-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, #c9883a, #e8704a);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Fraunces', serif;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }
        .sidebar-logo-text {
            display: flex;
            flex-direction: column;
        }
        .sidebar-logo-name {
            font-family: 'Fraunces', serif;
            font-size: 16px;
            font-weight: 700;
            color: #f5f0e8;
            line-height: 1;
            letter-spacing: -0.02em;
        }
        .sidebar-logo-sub {
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(245,240,232,0.3);
            margin-top: 2px;
        }

        /* ── User card ─────────────────────────────── */
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 12px 12px 0;
            padding: 10px 12px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 12px;
        }
        .sidebar-user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #c9883a, #e8704a);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }
        .sidebar-user-info { flex: 1; min-width: 0; }
        .sidebar-user-name {
            font-size: 12px;
            font-weight: 600;
            color: #f5f0e8;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-user-role {
            font-size: 10px;
            color: rgba(245,240,232,0.35);
            text-transform: capitalize;
            margin-top: 1px;
        }
        .sidebar-logout-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(245,240,232,0.3);
            padding: 4px;
            border-radius: 6px;
            transition: color 0.15s, background 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-logout-btn:hover {
            color: rgba(245,240,232,0.8);
            background: rgba(255,255,255,0.08);
        }

        /* ── Nav sections ──────────────────────────── */
        .sidebar-nav { flex: 1; padding: 10px 0 8px; }

        .sidebar-section-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(245,240,232,0.22);
            padding: 14px 16px 5px;
            display: block;
        }

        /* ── Nav items ─────────────────────────────── */
        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 14px;
            margin: 1px 8px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 500;
            color: rgba(245,240,232,0.52);
            text-decoration: none;
            transition: all 0.14s;
            position: relative;
            cursor: pointer;
            border: none;
            background: none;
            width: calc(100% - 16px);
            text-align: left;
        }
        .sidebar-item svg {
            flex-shrink: 0;
            opacity: 0.6;
            transition: opacity 0.14s;
        }
        .sidebar-item:hover {
            color: rgba(245,240,232,0.88);
            background: rgba(255,255,255,0.06);
        }
        .sidebar-item:hover svg { opacity: 0.9; }
        .sidebar-item.active {
            color: #fff;
            background: rgba(201,136,58,0.18);
            font-weight: 600;
        }
        .sidebar-item.active svg { opacity: 1; }
        .sidebar-item.active::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 18px;
            background: #c9883a;
            border-radius: 0 3px 3px 0;
        }

        /* ── Badges ────────────────────────────────── */
        .sidebar-badge {
            margin-left: auto;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px;
            line-height: 1.5;
            flex-shrink: 0;
        }
        .sidebar-badge-amber { background: #c9883a; color: #fff; }
        .sidebar-badge-red   { background: #e24b4a; color: #fff; }
        .sidebar-badge-gray  { background: rgba(255,255,255,0.12); color: rgba(245,240,232,0.7); }

        /* ── Sidebar footer ────────────────────────── */
        .sidebar-footer {
            padding: 10px 12px 14px;
            border-top: 1px solid rgba(255,255,255,0.07);
            margin-top: auto;
        }
        .sidebar-notif-btn {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 12px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 10px;
            text-decoration: none;
            color: rgba(245,240,232,0.55);
            font-size: 12px;
            font-weight: 500;
            transition: all 0.15s;
            width: 100%;
        }
        .sidebar-notif-btn:hover {
            background: rgba(255,255,255,0.09);
            color: rgba(245,240,232,0.9);
        }

        /* ══════════════════════════════════════════════
           MOBILE SIDEBAR
        ══════════════════════════════════════════════ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 99;
            backdrop-filter: blur(2px);
        }

        @media (max-width: 1024px) {
            .admin-sidebar {
                position: fixed;
                left: 0;
                top: 0;
                transform: translateX(-100%);
            }
            .admin-sidebar.open {
                transform: translateX(0);
                box-shadow: 8px 0 40px rgba(0,0,0,0.4);
            }
            .sidebar-overlay.open { display: block; }
            .admin-main { min-width: 0; }
        }

        /* ══════════════════════════════════════════════
           MAIN CONTENT
        ══════════════════════════════════════════════ */
        .admin-main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        /* ══════════════════════════════════════════════
           TOPBAR
        ══════════════════════════════════════════════ */
        .admin-topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            gap: 12px;
            background: rgba(247,246,243,0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(13,13,13,0.08);
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .topbar-hamburger {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            color: #7a7a7a;
            padding: 6px;
            border-radius: 8px;
            transition: background 0.15s, color 0.15s;
        }
        .topbar-hamburger:hover {
            background: rgba(13,13,13,0.06);
            color: #0d0d0d;
        }
        @media (max-width: 1024px) {
            .topbar-hamburger { display: flex; }
        }
        .topbar-back {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            color: #7a7a7a;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
            flex-shrink: 0;
        }
        .topbar-back:hover {
            background: rgba(13,13,13,0.06);
            color: #0d0d0d;
        }
        .topbar-title {
            font-family: 'Fraunces', serif;
            font-size: 17px;
            font-weight: 700;
            color: #0d0d0d;
            letter-spacing: -0.02em;
            white-space: nowrap;
        }
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ══════════════════════════════════════════════
           FLASH ALERTS
        ══════════════════════════════════════════════ */
        .flash-wrap { padding: 16px 20px 0; }
        .flash-success {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            color: #166534;
            font-size: 13px;
            font-weight: 500;
        }
        .flash-error {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            color: #991b1b;
            font-size: 13px;
        }
        .flash-warning {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 10px;
            color: #92400e;
            font-size: 13px;
        }

        /* ══════════════════════════════════════════════
           PAGE CONTENT
        ══════════════════════════════════════════════ */
        .admin-content {
            flex: 1;
            padding: 22px 20px 40px;
        }

        /* ══════════════════════════════════════════════
           TOAST
        ══════════════════════════════════════════════ */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            background: #0d0d0d;
            color: #f5f0e8;
            padding: 11px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.22);
            transform: translateY(80px);
            opacity: 0;
            pointer-events: none;
            transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1),
                        opacity 0.3s ease;
            max-width: 320px;
        }
        .toast.show {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        /* ══════════════════════════════════════════════
           EDITOR STYLES (used in create/edit post)
        ══════════════════════════════════════════════ */
        .editor-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 2px;
            padding: 8px 10px;
            background: #f7f6f3;
            border: 1px solid rgba(13,13,13,0.10);
            border-bottom: none;
            border-radius: 12px 12px 0 0;
        }
        .editor-btn {
            padding: 5px 9px;
            min-width: 30px;
            border-radius: 6px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            color: #4a4a4a;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background 0.12s, color 0.12s;
            text-align: center;
            line-height: 1.4;
        }
        .editor-btn:hover {
            background: #ede7d8;
            color: #0d0d0d;
        }
        .editor-sep {
            width: 1px;
            background: rgba(13,13,13,0.10);
            margin: 3px 3px;
            align-self: stretch;
        }
        [contenteditable]:empty:before {
            content: attr(data-placeholder);
            color: #aaa;
            pointer-events: none;
            display: block;
        }
        [contenteditable]:focus { outline: none; }

        /* ══════════════════════════════════════════════
           FORM COMPONENTS
        ══════════════════════════════════════════════ */
        .form-card {
            background: #fff;
            border: 1px solid rgba(13,13,13,0.08);
            border-radius: 16px;
            padding: 20px;
        }
        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #6a6a6a;
            margin-bottom: 6px;
        }
        .form-input {
            width: 100%;
            padding: 9px 13px;
            border: 1px solid rgba(13,13,13,0.13);
            border-radius: 9px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px;
            color: #0d0d0d;
            background: #fff;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-input:focus {
            border-color: #c9883a;
            box-shadow: 0 0 0 3px rgba(201,136,58,0.12);
        }
        .form-input::placeholder { color: #aaa; }
        .form-input.error { border-color: #e24b4a; }

        .form-select {
            width: 100%;
            padding: 9px 13px;
            border: 1px solid rgba(13,13,13,0.13);
            border-radius: 9px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px;
            color: #0d0d0d;
            background: #fff;
            outline: none;
            cursor: pointer;
            transition: border-color 0.15s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%236a6a6a' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 32px;
        }
        .form-select:focus {
            border-color: #c9883a;
            box-shadow: 0 0 0 3px rgba(201,136,58,0.12);
        }

        .form-textarea {
            width: 100%;
            padding: 9px 13px;
            border: 1px solid rgba(13,13,13,0.13);
            border-radius: 9px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px;
            color: #0d0d0d;
            background: #fff;
            outline: none;
            resize: vertical;
            transition: border-color 0.15s, box-shadow 0.15s;
            min-height: 90px;
        }
        .form-textarea:focus {
            border-color: #c9883a;
            box-shadow: 0 0 0 3px rgba(201,136,58,0.12);
        }
        .form-textarea::placeholder { color: #aaa; }

        /* ══════════════════════════════════════════════
           BUTTONS
        ══════════════════════════════════════════════ */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #0d0d0d;
            color: #f5f0e8;
            border: none;
            padding: 8px 18px;
            border-radius: 9px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s, transform 0.1s;
            white-space: nowrap;
            letter-spacing: 0.01em;
        }
        .btn-primary:hover { background: #1a1a1a; }
        .btn-primary:active { transform: scale(0.98); }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            color: #0d0d0d;
            border: 1px solid rgba(13,13,13,0.15);
            padding: 8px 18px;
            border-radius: 9px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: border-color 0.15s, background 0.15s;
            white-space: nowrap;
        }
        .btn-outline:hover {
            border-color: rgba(13,13,13,0.4);
            background: rgba(13,13,13,0.04);
        }

        .btn-amber {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #c9883a;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 9px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s;
        }
        .btn-amber:hover { background: #b57830; }

        /* ══════════════════════════════════════════════
           STATUS PILLS
        ══════════════════════════════════════════════ */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }
        .status-published { background: #dcfce7; color: #166534; }
        .status-draft     { background: #f3f4f6; color: #4b5563; }
        .status-scheduled { background: #dbeafe; color: #1e40af; }
        .status-archived  { background: #fef3c7; color: #92400e; }

        /* ══════════════════════════════════════════════
           DATA TABLE
        ══════════════════════════════════════════════ */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .data-table th {
            padding: 10px 14px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #7a7a7a;
            background: #f7f6f3;
            border-bottom: 1px solid rgba(13,13,13,0.08);
            white-space: nowrap;
        }
        .data-table td {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(13,13,13,0.05);
            vertical-align: middle;
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background: #fafaf8; }

        /* ══════════════════════════════════════════════
           TAG CHIPS
        ══════════════════════════════════════════════ */
        .tag-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #ede7d8;
            border-radius: 999px;
            padding: 3px 10px;
            font-size: 12px;
            font-weight: 500;
            color: #0d0d0d;
            user-select: none;
        }

        /* ══════════════════════════════════════════════
           STATUS OPTION (post form)
        ══════════════════════════════════════════════ */
        .status-opt {
            border: 1px solid rgba(13,13,13,0.10);
            border-radius: 10px;
            padding: 10px 8px;
            cursor: pointer;
            text-align: center;
            transition: all 0.15s;
            user-select: none;
        }
        .status-opt:hover {
            border-color: rgba(13,13,13,0.25);
            background: #fafaf8;
        }
        .status-opt.selected {
            border-color: #c9883a;
            background: #f5e4c3;
        }
        .status-opt.selected .status-opt-label { color: #c9883a; }
        .status-opt-icon { font-size: 18px; margin-bottom: 3px; }
        .status-opt-label {
            font-size: 11px;
            font-weight: 700;
            color: #4a4a4a;
            display: block;
        }
        .status-opt-desc {
            font-size: 9px;
            color: #9a9a9a;
            margin-top: 2px;
            display: block;
        }

        /* ══════════════════════════════════════════════
           SCROLLBAR CUSTOM
        ══════════════════════════════════════════════ */
        .admin-content::-webkit-scrollbar { width: 5px; }
        .admin-content::-webkit-scrollbar-track { background: transparent; }
        .admin-content::-webkit-scrollbar-thumb {
            background: rgba(13,13,13,0.12);
            border-radius: 3px;
        }

        /* ══════════════════════════════════════════════
           ANIMATION
        ══════════════════════════════════════════════ */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade { animation: fadeSlideUp 0.3s ease both; }
    </style>

@endpush

@push('scripts')

@endpush

@section('body-class', '')


@section('content')
   {{-- Mobile overlay --}}
    <div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebar()"></div>

    <div class="admin-layout">
        @include('customer.layouts.sidebar')
 {{-- Page content --}}
        <main class="admin-main">
            {{-- Topbar --}}
            <div class="admin-topbar">
                {{-- Left: Hamburger + Back + Title --}}
                <div class="topbar-left">
                    {{-- Mobile hamburger --}}
                    <button class="topbar-hamburger" onclick="openSidebar()"
                            aria-label="Open sidebar">
                        <svg width="18" height="18" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <line x1="3" y1="6"  x2="21" y2="6"/>
                            <line x1="3" y1="12" x2="21" y2="12"/>
                            <line x1="3" y1="18" x2="21" y2="18"/>
                        </svg>
                    </button>

                    {{-- Back button (optional, set $backUrl in view) --}}
                    @isset($backUrl)
                        <a href="{{ $backUrl }}" class="topbar-back" title="Go back">
                            <svg width="16" height="16" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path d="M19 12H5M12 19l-7-7 7-7"/>
                            </svg>
                        </a>
                    @endisset

                     {{-- Page title --}}
                    <h1 class="topbar-title">
                        @yield('page-title', 'Dashboard')
                    </h1>

                    {{-- Optional badge next to title --}}
                    @yield('page-badge')
                </div>

                {{-- Right: Action buttons --}}
                <div class="topbar-actions">
                    @yield('topbar-actions')
                </div>
            </div>

            {{-- Flash messages --}}
            @if(session()->hasAny(['success', 'error', 'warning']))
                <div class="flash-wrap">
                    @if(session('success'))
                        <div class="flash-success animate-fade"
                            x-data x-init="setTimeout(() => $el.remove(), 5000)">
                            <svg width="15" height="15" fill="none" stroke="currentColor"
                                stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="flash-error animate-fade">
                            <svg width="15" height="15" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="15" y1="9" x2="9" y2="15"/>
                                <line x1="9" y1="9" x2="15" y2="15"/>
                            </svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="flash-warning animate-fade">
                            <svg width="15" height="15" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                <line x1="12" y1="9" x2="12" y2="13"/>
                                <line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                            {{ session('warning') }}
                        </div>
                    @endif
                </div>
            @endif

            {{-- Page content --}}
            <div class="admin-content">
                @yield('customer-content')
            </div>
        </main>
    </div>

    {{-- Toast notification --}}
    <div id="toast" class="toast">
        <svg width="14" height="14" fill="none" stroke="currentColor"
            stroke-width="2.5" viewBox="0 0 24 24">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        <span id="toast-msg"></span>
    </div>

 @endsection

@push('after-scripts')
<script>
    // ── Sidebar toggle ────────────────────────────────────────
    function openSidebar() {
        document.getElementById('admin-sidebar').classList.add('open');
        document.getElementById('sidebar-overlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('admin-sidebar').classList.remove('open');
        document.getElementById('sidebar-overlay').classList.remove('open');
        document.body.style.overflow = '';
    }

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeSidebar();
    });

    // ── Toast ─────────────────────────────────────────────────
    function showToast(message, duration = 3000) {
        const toast = document.getElementById('toast');
        const msg   = document.getElementById('toast-msg');
        if (!toast || !msg) return;
        msg.textContent = message;
        toast.classList.add('show');
        clearTimeout(toast._timer);
        toast._timer = setTimeout(() => toast.classList.remove('show'), duration);
    }

    // ── Active link highlight on page load ────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        // Flash messages auto-dismiss
        document.querySelectorAll('.flash-success').forEach(el => {
            setTimeout(() => {
                el.style.transition = 'opacity 0.3s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 300);
            }, 5000);
        });
    });
</script>
@endpush
