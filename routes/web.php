<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Api\ApiPostController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/blog');
});


Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});


Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');


Route::middleware(['auth'])->group(function () {
    Route::post('/blog/{post}/comments', [CommentController::class, 'store'])->name('blog.comments.store');
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});


// Routes Admin
Route::prefix('admin')->name('admin.')->middleware(['web', 'auth', 'verified', 'admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::resource('posts', PostController::class);
    Route::post('/posts/bulk', [PostController::class, 'bulk'])->name('posts.bulk');
    Route::post('/posts/{post}/duplicate', [PostController::class, 'duplicate'])->name('posts.duplicate');
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
