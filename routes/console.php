<?php

use App\Services\Customer\InviteService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Schedule invite expiration check to run hourly.
 * This marks pending invites as expired when their expires_at timestamp passes.
 */
Schedule::call(function () {
    app(InviteService::class)->expireStale();
})->hourly()->name('expire-stale-invites');
