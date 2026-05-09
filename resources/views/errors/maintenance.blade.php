<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance — {{ \App\Models\Setting::get('site_name', 'Inkwell') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap"
          rel="stylesheet">

    @php
        $themeColor = \App\Models\Setting::get('theme_color', '#c9883a');
        $siteName   = \App\Models\Setting::get('site_name', 'Inkwell');
    @endphp

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --amber: {{ $themeColor }};
            --ink: #0d0d0d;
            --cream: #f5f0e8;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: var(--ink);
            color: var(--cream);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* ── Animated background ──────────────────────────── */
        .bg-layer {
            position: fixed;
            inset: 0;
            z-index: 0;
        }
        .bg-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(245,240,232,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(245,240,232,0.03) 1px, transparent 1px);
            background-size: 48px 48px;
        }
        .bg-glow-1 {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,136,58,0.15), transparent 70%);
            top: -200px;
            right: -200px;
            animation: float 8s ease-in-out infinite;
        }
        .bg-glow-2 {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,136,58,0.08), transparent 70%);
            bottom: -100px;
            left: -100px;
            animation: float 10s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-30px) scale(1.05); }
        }

        /* ── Content ──────────────────────────────────────── */
        .content {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 2rem;
            max-width: 520px;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 3rem;
            text-decoration: none;
        }
        .logo-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--amber);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Fraunces', serif;
            font-size: 22px;
            font-weight: 700;
            color: #fff;
        }
        .logo-name {
            font-family: 'Fraunces', serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--cream);
            letter-spacing: -0.02em;
        }

        /* ── Gear animation ───────────────────────────────── */
        .gear-wrap {
            margin: 0 auto 2rem;
            width: 80px;
            height: 80px;
            position: relative;
        }
        .gear {
            width: 80px;
            height: 80px;
            animation: spin 4s linear infinite;
            color: var(--amber);
        }
        .gear-inner {
            width: 50px;
            height: 50px;
            animation: spin 4s linear infinite reverse;
            color: rgba(201,136,58,0.4);
            position: absolute;
            top: 15px;
            left: 15px;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        h1 {
            font-family: 'Fraunces', serif;
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 700;
            color: var(--cream);
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 1rem;
        }

        .message {
            font-size: 1rem;
            color: rgba(245,240,232,0.55);
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        /* ── Progress bar ─────────────────────────────────── */
        .progress-wrap {
            background: rgba(245,240,232,0.08);
            border-radius: 999px;
            height: 4px;
            overflow: hidden;
            margin-bottom: 2.5rem;
        }
        .progress-bar {
            height: 4px;
            background: var(--amber);
            border-radius: 999px;
            animation: progress 3s ease-in-out infinite;
        }
        @keyframes progress {
            0%   { width: 5%; }
            50%  { width: 70%; }
            100% { width: 95%; }
        }

        /* ── Status badge ─────────────────────────────────── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(245,240,232,0.06);
            border: 1px solid rgba(245,240,232,0.1);
            border-radius: 999px;
            padding: 8px 18px;
            font-size: 13px;
            color: rgba(245,240,232,0.5);
            margin-bottom: 2rem;
        }
        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--amber);
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.4; transform: scale(0.8); }
        }

        /* ── Social links ─────────────────────────────────── */
        .socials {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 2.5rem;
        }
        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(245,240,232,0.06);
            border: 1px solid rgba(245,240,232,0.1);
            color: rgba(245,240,232,0.4);
            text-decoration: none;
            font-size: 13px;
            transition: all 0.2s;
        }
        .social-link:hover {
            background: rgba(201,136,58,0.15);
            border-color: rgba(201,136,58,0.3);
            color: var(--amber);
            transform: translateY(-2px);
        }

        /* ── Admin login link ─────────────────────────────── */
        .admin-link {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 11px;
            color: rgba(245,240,232,0.2);
            text-decoration: none;
            transition: color 0.2s;
        }
        .admin-link:hover { color: rgba(245,240,232,0.5); }
    </style>
</head>
<body>

{{-- Background --}}
<div class="bg-layer">
    <div class="bg-grid"></div>
    <div class="bg-glow-1"></div>
    <div class="bg-glow-2"></div>
</div>

{{-- Content --}}
<div class="content">

    {{-- Logo --}}
    <div class="logo">
        @if(\App\Models\Setting::get('site_logo'))
        <img src="{{ \Storage::disk('public')->url(\App\Models\Setting::get('site_logo')) }}"
             alt="{{ $siteName }}" style="height:40px;object-fit:contain">
        @else
        <div class="logo-icon">
            {{ strtoupper(substr($siteName, 0, 1)) }}
        </div>
        <span class="logo-name">{{ $siteName }}</span>
        @endif
    </div>

    {{-- Animated gear --}}
    <div class="gear-wrap">
        <svg class="gear" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.5">
            <circle cx="12" cy="12" r="3"/>
            <path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/>
            <path d="M12 2v2M12 20v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M2 12h2M20 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
        </svg>
    </div>

    {{-- Status --}}
    <div class="status-badge">
        <div class="status-dot"></div>
        Maintenance in progress
    </div>

    {{-- Heading --}}
    <h1>
        We'll be back
        <br>
        <em style="color: var(--amber)">very soon</em>
    </h1>

    {{-- Message --}}
    <p class="message">
        {{ $message }}
    </p>

    {{-- Progress --}}
    <div class="progress-wrap">
        <div class="progress-bar"></div>
    </div>

    {{-- Contact --}}
    @php $email = \App\Models\Setting::get('site_email'); @endphp
    @if($email)
    <p style="font-size:13px;color:rgba(245,240,232,0.3)">
        Need help? Reach us at
        <a href="mailto:{{ $email }}"
           style="color:var(--amber);text-decoration:none">
            {{ $email }}
        </a>
    </p>
    @endif

    {{-- Social links --}}
    @php
        $socials = [
            'social_twitter'   => '𝕏',
            'social_github'    => '⌘',
            'social_linkedin'  => 'in',
            'social_instagram' => '◎',
        ];
    @endphp
    <div class="socials">
        @foreach($socials as $key => $icon)
        @if(\App\Models\Setting::get($key))
        <a href="{{ \App\Models\Setting::get($key) }}"
           target="_blank" rel="noopener"
           class="social-link">
            {{ $icon }}
        </a>
        @endif
        @endforeach
    </div>
</div>

{{-- Admin back door --}}
<a href="{{ route('login') }}" class="admin-link">
    Admin Login →
</a>

</body>
</html>
