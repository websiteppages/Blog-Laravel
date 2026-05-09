<?php

namespace App\Providers;

use App\Events\MemberInvited;
use App\Events\ModelViewed;
use App\Events\PostPublished;
use App\Listeners\LogModelAction;
use App\Listeners\RecordViewActivity;
use App\Listeners\SendInviteEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MemberInvited::class => [SendInviteEmail::class,],

        // ModelViewed is handled by a queued listener so it adds
        // zero latency to the HTTP response that triggered it.
        ModelViewed::class => [RecordViewActivity::class,],
    ];

    protected $subscribe = [
        // LogModelAction subscribes to PostPublished and MemberInvited
        // via its subscribe() method — audit logging for domain events.
        LogModelAction::class,
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
