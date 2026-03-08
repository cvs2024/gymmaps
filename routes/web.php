<?php

use App\Http\Controllers\Admin\ImportMonitorController;
use App\Http\Controllers\GymbuddyPostController;
use App\Http\Controllers\ListingRequestController;
use App\Http\Controllers\LocationProfileController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SearchController::class, 'index'])->name('home');

Route::get('/aanmelden', [ListingRequestController::class, 'create'])->name('listing-requests.create');
Route::post('/aanmelden', [ListingRequestController::class, 'store'])->name('listing-requests.store');
Route::get('/gymbuddy', [GymbuddyPostController::class, 'index'])->name('gymbuddy.index');
Route::post('/gymbuddy', [GymbuddyPostController::class, 'store'])->name('gymbuddy.store');
Route::get('/sportschool/{location}', [LocationProfileController::class, 'show'])->name('locations.show');

Route::get('/admin/imports', [ImportMonitorController::class, 'index'])
    ->middleware('admin.imports.auth')
    ->name('admin.imports.index');
