<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        // Start with published posts query
        $query = Post::with(['user', 'category'])
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by tag
        if ($request->filled('tag')) {
            $query->where('meta_keywords', 'like', '%' . $request->tag . '%');
        }

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('content', 'like', '%' . $searchTerm . '%')
                  ->orWhere('excerpt', 'like', '%' . $searchTerm . '%')
                  ->orWhere('meta_keywords', 'like', '%' . $searchTerm . '%');
            });
        }

        // Get featured and regular posts
        $featuredPost = null;
        if (!$request->filled('page') && !$request->filled('search') && !$request->filled('category') && !$request->filled('tag')) {
            $featuredPost = $query->clone()
                ->where('is_featured', true)
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->latest('published_at')
                ->first();

            if ($featuredPost) {
                $query->where('id', '!=', $featuredPost->id);
            }
        }

        // Get regular posts
        $posts = $query->latest('published_at')->paginate(10);

        // Get categories with post count
        $categories = Category::withCount(['posts' => function($query) {
            $query->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now());
        }])->get();

        // Get popular posts
        $popularPosts = Post::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();

        // Get popular tags
        $tags = Post::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->whereNotNull('meta_keywords')
            ->get()
            ->pluck('meta_keywords')
            ->flatMap(function ($keywords) {
                return array_map('trim', explode(',', $keywords));
            })
            ->countBy()
            ->sortDesc()
            ->take(15);

        return view('blog.index', compact(
            'posts',
            'featuredPost',
            'categories',
            'popularPosts',
            'tags'
        ));
    }

    public function show(Request $request, $slug)
    {
        $post = Post::with(['user', 'category'])
            ->where('slug', $slug)
            ->where(function($query) {
                $query->where('status', 'published')
                    ->orWhere(function($q) {
                        $q->where('status', 'draft')
                            ->where('user_id', Auth::id());
                    });
            })
            ->firstOrFail();

        // Increment view count
        if (!$request->session()->has('viewed_post_' . $post->id)) {
            $post->increment('views');
            $request->session()->put('viewed_post_' . $post->id, true);
        }

        // Calculate reading time
        $words = str_word_count(strip_tags($post->content));
        $readingTime = ceil($words / 200); // Assuming 200 words per minute

        // Get related posts
        $relatedPosts = Post::where('id', '!=', $post->id)
            ->where('status', 'published')
            ->where('category_id', $post->category_id)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->limit(2)
            ->get();

        // Get popular posts
        $popularPosts = Post::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();

        // Generate table of contents from content
        $tableOfContents = [];
        if (preg_match_all('/<h([2-3])[^>]*>(.*?)<\/h\1>/i', $post->content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $level = $match[1];
                $text = strip_tags($match[2]);
                $id = 'heading-' . Str::slug($text);

                // Add id to the heading in content
                $post->content = str_replace(
                    $match[0],
                    "<h{$level} id=\"{$id}\">{$match[2]}</h{$level}>",
                    $post->content
                );

                $tableOfContents[] = [
                    'level' => $level,
                    'text' => $text,
                    'id' => $id
                ];
            }
        }

        return view('blog.show', compact(
            'post',
            'readingTime',
            'relatedPosts',
            'popularPosts',
            'tableOfContents'
        ));
    }
}
