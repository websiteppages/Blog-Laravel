<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    <link rel="icon" type="image/png" href="{{ asset($favicon ?? 'favicon.png') }}">

    <!-- Primary Meta -->
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', config('app.name'))">
    <meta name="keywords" content="@yield('meta_keywords', '')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <link rel="canonical" href="@yield('canonical', url()->current())" />

    <!-- Open Graph -->
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="@yield('title', config('app.name'))" />
    <meta property="og:description" content="@yield('meta_description', config('app.name'))" />
    <meta property="og:image" content="@yield('meta_image', asset('default-og.png'))" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="@yield('title', config('app.name'))" />
    <meta name="twitter:description" content="@yield('meta_description', config('app.name'))" />
    <meta name="twitter:image" content="@yield('meta_image', asset('default-og.png'))" />

    <!-- Schema.org -->
    <meta itemprop="name" content="@yield('title', config('app.name'))" />
    <meta itemprop="description" content="@yield('meta_description', config('app.name'))" />
    <meta itemprop="image" content="@yield('meta_image', asset('default-og.png'))" />

    @stack('styles')
</head>

<body class="@yield('body-class', 'bg-white text-gray-900')">

    @yield('content')

    @stack('scripts')
    @stack('after-scripts')
</body>
</html>
