<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class ApiPostController extends Controller
{
    /**
     * Display a listing of published posts
     */
    public function index(Request $request)
    {
        $query = Post::with('user:id,name')
            ->published()
            ->recent();

        // Filter by category if provided
        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $posts->items(),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ]
        ]);
    }

    /**
     * Display the specified post by slug
     */
    public function show($slug)
    {
        $post = Post::with('user:id,name')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $post
        ]);
    }

    /**
     * Get available categories with post counts
     */
    public function categories()
    {
        $categories = Post::published()
            ->join('categories', 'posts.category_id', '=', 'categories.id')
            ->select('categories.id', 'categories.name', 'categories.slug')
            ->selectRaw('COUNT(posts.id) as post_count')
            ->groupBy('categories.id', 'categories.name', 'categories.slug')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'post_count' => $category->post_count
                ];
            })
        ]);
    }
}
