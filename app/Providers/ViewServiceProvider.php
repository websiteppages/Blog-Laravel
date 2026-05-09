<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Services\Admin\SettingsService;


class ViewServiceProvider extends ServiceProvider
{
    public function boot(SettingsService $settings): void
    {
         View::share('appSettings', $settings->all());
    }
}
