<?php

use App\Http\Controllers\Admin\ImportMonitorController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GymbuddyPostController;
use App\Http\Controllers\ListingRequestController;
use App\Http\Controllers\LocationProfileController;
use App\Http\Controllers\PersonalTrainerRequestController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SearchController::class, 'index'])->name('home');
Route::get('/zoek/suggesties', [SearchController::class, 'suggestions'])->name('home.suggestions');
Route::view('/login', 'pages.login')->name('login');
Route::get('/personal-trainer-zoeken', [PersonalTrainerRequestController::class, 'index'])->name('pages.personal-trainer');
Route::post('/personal-trainer-zoeken', [PersonalTrainerRequestController::class, 'store'])->name('pages.personal-trainer.store');
Route::view('/blog', 'pages.blog')->name('pages.blog');
Route::view('/blog/de-beste-sportscholen-in-nederland', 'pages.blogs.best-sportscholen')->name('pages.blog.best-sportscholen');
Route::view('/blog/sportschool-in-de-buurt-vinden', 'pages.blogs.sportschool-in-de-buurt')->name('pages.blog.sportschool-in-de-buurt');
Route::view('/blog/waarom-sporten-met-een-gymbuddy-effectief-is', 'pages.blogs.gymbuddy-effectief')->name('pages.blog.gymbuddy-effectief');
Route::view('/blog/hoe-kies-je-een-goede-personal-trainer', 'pages.blogs.goede-personal-trainer')->name('pages.blog.goede-personal-trainer');
Route::view('/faq', 'pages.faq')->name('pages.faq');
Route::get('/contact', [ContactController::class, 'index'])->name('pages.contact');
Route::post('/contact', [ContactController::class, 'store'])->name('pages.contact.store');

Route::get('/aanmelden', [ListingRequestController::class, 'create'])->name('listing-requests.create');
Route::post('/aanmelden', [ListingRequestController::class, 'store'])->name('listing-requests.store');
Route::get('/gymbuddy', [GymbuddyPostController::class, 'index'])->name('gymbuddy.index');
Route::post('/gymbuddy', [GymbuddyPostController::class, 'store'])->name('gymbuddy.store');
Route::get('/gymbuddy/{post}/foto', [GymbuddyPostController::class, 'photo'])->name('gymbuddy.photo');
Route::get('/sportschool/{location}', [LocationProfileController::class, 'show'])->name('locations.show');

Route::get('/admin/imports', [ImportMonitorController::class, 'index'])
    ->middleware('admin.imports.auth')
    ->name('admin.imports.index');
Route::post('/admin/imports/run-kvk', [ImportMonitorController::class, 'runKvkImport'])
    ->middleware('admin.imports.auth')
    ->name('admin.imports.run-kvk');
