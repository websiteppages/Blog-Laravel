<?php

if (!function_exists('active_route')) {
    function active_route(string $pattern): bool
    {
        return request()->routeIs($pattern);
    }
}
