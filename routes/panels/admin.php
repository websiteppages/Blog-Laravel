<?php

use App\Http\Controllers\Admin\OverviewController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;


Route::get('/', [OverviewController::class, 'index'])->name('overview');
Route::resource('roles', RoleController::class)
         ->names('roles');
         //->middleware('permission:view roles');
