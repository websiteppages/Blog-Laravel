<?php

use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\Workspace\MemberController;
use App\Http\Controllers\Customer\Workspace\WorkspaceController;
use App\Http\Controllers\Customer\Workspace\WorkspaceRoleController;
use Illuminate\Support\Facades\Route;

// Dashboard — requires an active workspace

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');



// ─── Public invite acceptance ─────────────────────────────────────────────────
Route::get('/invites/{token}/accept', [MemberController::class, 'acceptInvite'])->name('invites.accept');

Route::resource('workspaces', WorkspaceController::class)->only(['create', 'store']);

Route::middleware('workspace.access')->group(function () {
    Route::resource('workspaces', WorkspaceController::class)->except(['create', 'store']);

    Route::post('workspaces/{workspace}/switch', [WorkspaceController::class, 'switch'])->name('workspaces.switch');
    // Members & Invites
    Route::prefix('workspaces/{workspace}')->name('workspaces.')->group(function () {
        Route::get('members', [MemberController::class, 'index'])->name('members');
        Route::post('members/invite', [MemberController::class, 'invite'])->name('members.invite');
        Route::delete('members/{user}', [MemberController::class, 'remove'])->name('members.remove');
        Route::put('members/{user}/role', [MemberController::class, 'changeRole'])->name('members.role');
        Route::delete('invites/{invite}/revoke', [MemberController::class, 'revokeInvite'])->name('invites.revoke');

        // Roles
        Route::resource('roles', WorkspaceRoleController::class)->except(['show']);
    });

    // Admin section
    // Route::prefix('admin')->name('admin.')->group(function () {
    //     Route::get('settings', [SettingsController::class, 'index'])->name('settings');
    //     Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    //     Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs');
    //     Route::get('view-analytics', [AuditLogController::class, 'viewAnalytics'])->name('view-analytics');
    // });

});
