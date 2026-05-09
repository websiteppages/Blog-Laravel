<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      data-theme="{{ $appSettings['theme_mode'] ?? 'light' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Security Headers -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    {{-- <meta http-equiv="X-Frame-Options" content="SAMEORIGIN"> --}}
    <meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
    <meta http-equiv="Permissions-Policy" content="geolocation=(), microphone=(), camera=()">

    <!-- Content Security Policy -->
    {{-- <meta http-equiv="Content-Security-Policy"
        content="default-src 'self';
          script-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com;
          script-src-attr 'none';
          style-src 'self' https://fonts.googleapis.com 'unsafe-inline';
          img-src 'self' data: https:;
          font-src 'self' https://fonts.gstatic.com; connect-src 'self' https:;
          form-action 'self';
          frame-ancestors 'none';
          base-uri 'self';
          object-src 'none';
          upgrade-insecure-requests;"
    > --}}


    <!-- Favicon -->
    @if(! empty($appSettings['site_favicon']))
        <link rel="icon" type="image/png" href="{{ Storage::disk('public')->url($appSettings['site_favicon']) }}">
    @endif


    @if(($appSettings['seo_sitemap'] ?? '0') === '1')
        <link rel="sitemap" type="application/xml" href="{{ route('sitemap.index') }}">
    @endif

    <!-- Primary Meta -->
    <title>
        @hasSection('title')
            @yield('title') — {{ $appSettings['site_name'] ?? config('app.name') }}
        @else
            {{ $appSettings['site_name'] ?? config('app.name') }}
        @endif
    </title>
    <meta name="description" content="@yield('meta_description', $appSettings['site_description'] ?? '') ">
    <meta name="keywords" content="@yield('meta_keywords', '')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <link rel="canonical" href="@yield('canonical', url()->current())" />

    <!-- Open Graph -->
    <meta property="og:site_name" content="{{ $appSettings['site_name'] ?? config('app.name') }}">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', $appSettings['site_name'] ?? config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', $appSettings['site_description'] ?? '')">
    <meta property="og:image" content="@yield('meta_image', asset('images/default-og.png'))">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', $appSettings['site_name'] ?? config('app.name'))">
    <meta name="twitter:description" content="@yield('meta_description', $appSettings['site_description'] ?? '')">
    <meta name="twitter:image" content="@yield('meta_image', asset('images/default-og.png'))">

    <!-- Schema.org -->
    <meta itemprop="name" content="@yield('title', $appSettings['site_name'] ?? config('app.name'))">
    <meta itemprop="description" content="@yield('meta_description', $appSettings['site_description'] ?? '')">
    <meta itemprop="image" content="@yield('meta_image', asset('images/default-og.png'))">

    <!-- Theme -->
    <meta name="theme-color" content="{{ $appSettings['theme_color'] ?? '' }}">

    {{-- Brand color CSS variable --}}
    @php
        $themeMode = $appSettings['theme_mode'] ?? 'light';
        $darkThemeCss = '';
        if ($themeMode === 'dark') {
            $darkThemeCss = '
                html[data-theme="dark"] body {
                    background-color: #0d0d0d;
                    color: #f5f0e8;
                }
            ';
        }
        if ($themeMode === 'system') {
            $darkThemeCss = '
                @media (prefers-color-scheme: dark) {

                    html[data-theme="system"] body {

                        background-color: #0d0d0d;
                        color: #f5f0e8;
                    }
                }
            ';
        }
    @endphp
<style>
    :root {
        --color-primary:
            {{ $appSettings['theme_color'] ?? '#c9883a' }};
        --color-primary-light:
            {{ $appSettings['theme_color_light'] ?? '#ddb074' }};
        --color-primary-pale:
            {{ $appSettings['theme_color_pale'] ?? '#f5e7d2' }};
    }
    {!! $darkThemeCss !!}
</style>


    @stack('styles')
</head>

<body class="@yield('body-class', 'bg-white text-gray-900')">

    {{-- Maintenance mode notice for non-admins --}}
    {{-- @if(
        ($appSettings['maintenance_mode'] ?? '0') === '1'
        && ! auth()->user()?->canBypassMaintenance()
    )
        <div class="fixed inset-0 z-[9999] bg-ink flex items-center justify-center text-center p-8">
            <div>
                <div class="text-6xl mb-6"> 🔧 </div>
                <h1 class="font-display text-3xl font-bold text-cream mb-3">
                    Under Maintenance
                </h1>
                <p class="text-cream/60 max-w-sm">
                    {{ $appSettings['maintenance_message'] ?? "We'll be back shortly!" }}
                </p>
            </div>
        </div>
    @endif --}}


    @yield('content')

    @stack('scripts')
    @stack('after-scripts')
</body>
</html>
