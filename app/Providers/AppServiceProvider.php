<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the blog-layout component
        Blade::component('blog-layout', \App\View\Components\BlogLayout::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share data with admin layout
        View::composer('layouts.admin', function ($view) {
            $view->with([
                'postsCount' => Post::count(),
                'categories' => Category::all(),
            ]);
        });

        // Share categories with post forms
        View::composer(['admin.posts.create', 'admin.posts.edit'], function ($view) {
            $view->with([
                'categories' => Category::all(),
            ]);
        });

        // Share categories with posts index for filtering
        View::composer('admin.posts.index', function ($view) {
            $view->with([
                'categories' => Category::all(),
            ]);
        });
    }
}
