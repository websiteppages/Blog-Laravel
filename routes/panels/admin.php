<?php

use App\Http\Controllers\Admin\OverviewController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

use App\Enums\Permission as Perm;
use App\Http\Controllers\Admin\SettingsController;
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


 // Settings
    Route::get('settings',  [SettingsController::class, 'index'])
         ->name('settings')
         ->middleware('permission:view settings');
    Route::post('settings', [SettingsController::class, 'update'])
         ->name('settings.update')
         ->middleware('permission:edit settings');
    Route::post('settings/cache', function () {
        $type = request('type', 'all');
        match($type) {
            'config'      => \Artisan::call('config:clear'),
            'views'       => \Artisan::call('view:clear'),
            'permissions' => app(\Spatie\Permission\PermissionRegistrar::class)
                                ->forgetCachedPermissions(),
            default       => (function () {
                \Artisan::call('cache:clear');
                \Artisan::call('config:clear');
                \Artisan::call('view:clear');
                app(\Spatie\Permission\PermissionRegistrar::class)
                    ->forgetCachedPermissions();
            })(),
        };
        return response()->json(['message' => ucfirst($type) . ' cache cleared!']);
    })->name('settings.cache')->middleware('permission:edit settings');
