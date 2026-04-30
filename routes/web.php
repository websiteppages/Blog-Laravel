<?php

use Illuminate\Support\Facades\Route;
use App\Enums\Permission as Perm;

require __DIR__.'/auth.php';
require __DIR__.'/panels/website.php';

Route::middleware(['auth', 'verified'])->group(function () {

    // Customer Panel
    Route::prefix('customer')
        ->name('customer.')
        ->group(function () {
            require __DIR__.'/panels/customer.php';
        });

    // Admin Panel
    Route::prefix('admin')
        ->middleware(['permission:' . Perm::AccessDashboard->value])
        ->name('admin.')
        ->group(function () {
            require __DIR__.'/panels/admin.php';
        });

});
