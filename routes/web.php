<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// For non-authenticated users
Route::get('/', [PostController::class, 'index'])->name('index');

Route::get('/search', [PostController::class, 'search'])->name('posts.search');

// For authenticated users
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/dashboard', [PostController::class, 'dashboard'])->name('dashboard');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{postId}/comments', [PostController::class, 'addComment'])->name('posts.addComment');



    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Additional routes for ProfileController
    Route::get('/index', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/create', [ProfileController::class, 'create'])->name('profile.create');
    Route::get('/show/{id}', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/store', [ProfileController::class, 'store'])->name('profile.store');
});

// For failed authentication
Route::fallback(function () {
    return view('layout', ['auth' => false]); // Pass 'auth' variable as false to indicate failed authentication
});

// Additional route to handle logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('index');
})->name('logout');

require __DIR__.'/auth.php';
