<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoverPhotoController;
use App\Http\Controllers\ProfilePictureController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::view('/', 'feed')->name('home');
Route::view('/profile', 'profile')->name('profile');
Route::view('/friends', 'friends')->name('friends');
Route::view('/messages', 'messages')->name('messages');
Route::view('/notifications', 'notifications')->name('notifications');
Route::view('/settings', 'settings')->name('settings')->middleware('auth');

Route::middleware('guest')->controller(AuthController::class)->group(function (): void {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->middleware('throttle:5,1')->name('login.store');
    Route::get('/register', 'showRegistration')->name('register');
    Route::post('/register', 'register')->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::post('/profile/picture', ProfilePictureController::class)
    ->middleware('auth')
    ->name('profile.picture.update');

Route::post('/profile/cover', CoverPhotoController::class)
    ->middleware('auth')
    ->name('profile.cover.update');

Route::get('/file/{filename}', function ($filename) {
    $file = 'imgs/'.$filename;

    return Storage::disk('local')->response($file);
})->middleware('auth');
