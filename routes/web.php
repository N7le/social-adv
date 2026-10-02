<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoverPhotoController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePictureController;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', MainController::class)->name('home');
Route::get('/profile', function (Request $request): RedirectResponse {
    return redirect()->route('profile.show', ['username' => $request->user()->username]);
})->middleware('auth')->name('profile');
Route::get('/profile/{username:username}', ProfileController::class)->name('profile.show');
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

Route::post('/posts', [PostController::class, 'store'])
    ->middleware('auth')
    ->name('posts.store');

Route::get('/file/posts/{filename}', function (string $filename) {
    $file = 'posts/'.$filename;
    $post = Post::where('media_path', $file)->firstOrFail();

    Gate::authorize('view', $post);

    return Storage::disk('local')->response($file);
});
