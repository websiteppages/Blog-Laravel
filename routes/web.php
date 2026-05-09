<?php

use Illuminate\Support\Facades\Route;
use App\Enums\Permission as Perm;
use App\Http\Controllers\SitemapController;

require __DIR__.'/auth.php';

//Sitemap Routes (Public)

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-posts.xml', [SitemapController::class, 'posts'])->name('sitemap.posts');
Route::get('/sitemap-categories.xml', [SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/sitemap-tags.xml', [SitemapController::class, 'tags'])->name('sitemap.tags');

require __DIR__.'/panels/website.php';

Route::middleware(['auth', 'verified','load.relations'])->group(function () {

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
