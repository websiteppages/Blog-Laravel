<?php

use App\Http\Controllers\Admin\OverviewController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

use App\Enums\Permission as Perm;
use App\Http\Controllers\Admin\UserController;

Route::get('/', [OverviewController::class, 'index'])->name('overview');
// roles ------------------------
    Route::resource('roles', RoleController::class)
         ->names('roles')
         ->middleware('permission:' . Perm::ViewRoles->value);
// users --------------------------
    Route::resource('users', UserController::class)
         ->names('users')
         ->middleware('permission:' . Perm::ViewUsers->value);

    Route::post('/users/{user}/remove-role', [UserController::class, 'removeRole'])
        ->name('users.removeRole')
        ->middleware('permission:' . Perm::ManageRoles->value);
