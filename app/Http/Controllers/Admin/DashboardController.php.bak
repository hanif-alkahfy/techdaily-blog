<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get dashboard statistics
        $stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'total_categories' => Category::count(),
            'total_users' => User::count(),
        ];

        // Get recent posts
        $recent_posts = Post::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        // Get posts by status for chart
        $posts_by_status = [
            'published' => $stats['published_posts'],
            'draft' => $stats['draft_posts'],
        ];

        // Get popular posts (you can adjust this logic based on your needs)
        $popular_posts = Post::with(['user', 'category'])
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recent_posts',
            'posts_by_status',
            'popular_posts'
        ));
    }
}
