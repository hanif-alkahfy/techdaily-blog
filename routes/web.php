<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Api\PostController as ApiPostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes - protected by auth middleware
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin posts management
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('posts', PostController::class);
    });
});

// API routes
Route::prefix('api')->group(function () {
    Route::prefix('posts')->group(function () {
        Route::get('/', [ApiPostController::class, 'index']);
        Route::get('/categories', [ApiPostController::class, 'categories']);
        Route::get('/{slug}', [ApiPostController::class, 'show']);
    });
});

require __DIR__.'/auth.php';
