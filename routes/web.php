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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

// Redirect /dashboard to /admin/dashboard
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified']);

// Admin routes - protected by auth middleware
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('posts', PostController::class);
        // Bulk actions for posts
        Route::post('/posts/bulk', [PostController::class, 'bulk'])->name('posts.bulk');
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
