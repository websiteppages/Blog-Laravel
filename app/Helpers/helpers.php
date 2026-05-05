<?php

if (!function_exists('format_number')) {
    function format_number(int|float $n): string
    {
        if ($n >= 1_000_000) return round($n / 1_000_000, 1) . 'M';
        if ($n >= 1_000)     return round($n / 1_000, 1) . 'k';
        return (string) $n;
    }
}

if (!function_exists('time_ago')) {
    function time_ago(\Carbon\Carbon|string $date): string
    {
        $carbon = $date instanceof \Carbon\Carbon
            ? $date
            : \Carbon\Carbon::parse($date);
        return $carbon->diffForHumans();
    }
}

if (!function_exists('reading_time')) {
    function reading_time(string $content, int $wpm = 200): int
    {
        $words = str_word_count(strip_tags($content));
        return max(1, (int) ceil($words / $wpm));
    }
}

if (!function_exists('excerpt')) {
    function excerpt(?string $content, int $length = 160): string
    {
        if (!$content) return '';
        $text = strip_tags($content);
        return strlen($text) <= $length
            ? $text
            : rtrim(substr($text, 0, $length), ' .,') . '…';
    }
}

if (!function_exists('storage_url')) {
    function storage_url(?string $path): ?string
    {
        return $path
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($path)
            : null;
    }
}

if (!function_exists('initials')) {
    function initials(string $name): string
    {
        return collect(explode(' ', trim($name)))
            ->filter()
            ->take(2)
            ->map(fn($p) => strtoupper($p[0]))
            ->implode('');
    }
}

if (!function_exists('active_route')) {
    function active_route(string $pattern): bool
    {
        return request()->routeIs($pattern);
    }
}

// if (!function_exists('setting')) {
//     function setting(string $key, mixed $default = null): mixed
//     {
//         return \App\Models\Setting::get($key, $default);
//     }
// }
