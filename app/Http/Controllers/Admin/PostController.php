<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of posts
     */
    public function index()
    {
        $posts = Post::with('user')
            ->recent()
            ->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post
     */
    public function create()
    {
        $categories = $this->getCategories();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'excerpt' => 'nullable|max:500',
            'content' => 'required',
            'category' => 'required|max:100',
            'status' => 'required|in:draft,published'
        ]);

        $validated['user_id'] = Auth::id();

        Post::create($validated);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified post
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post
     */
    public function edit(Post $post)
    {
        $categories = $this->getCategories();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified post
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'excerpt' => 'nullable|max:500',
            'content' => 'required',
            'category' => 'required|max:100',
            'status' => 'required|in:draft,published'
        ]);

        $post->update($validated);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified post
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post deleted successfully!');
    }

    /**
     * Get available categories
     */
    private function getCategories()
    {
        return [
            'Tutorial' => 'Tutorial',
            'Opinion' => 'Opinion',
            'Review' => 'Review',
            'News' => 'News',
            'Tips' => 'Tips'
        ];
    }
}
