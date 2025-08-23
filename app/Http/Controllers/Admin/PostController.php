<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of posts
     */
    public function index(Request $request)
    {
        $query = Post::with(['user', 'category'])->latest();

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category if provided
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('content', 'like', '%' . $searchTerm . '%')
                  ->orWhere('excerpt', 'like', '%' . $searchTerm . '%');
            });
        }

        // Handle per page option
        $perPage = $request->get('per_page', 10);
        $posts = $query->paginate($perPage);
        $categories = Category::all();

        return view('admin.posts.index', [
            'posts' => $posts,
            'categories' => $categories,
            'status' => $request->status,
            'selectedCategory' => $request->category
        ]);
    }

    /**
     * Show the form for creating a new post
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created post
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|max:255',
                'slug' => 'nullable|unique:posts',
                'excerpt' => 'nullable',
                'content' => 'required',
                'category_id' => 'required|exists:categories,id',
                'status' => 'required|in:draft,published',
                'meta_title' => 'nullable|max:60',
                'meta_description' => 'nullable|max:160',
                'meta_keywords' => 'nullable',
                'published_at' => 'nullable|date',
                'featured_image' => 'nullable|image|max:5120' // 5MB max
            ]);

            DB::beginTransaction();

            // Generate unique slug if not provided
            if (empty($validated['slug'])) {
                $slug = Str::slug($validated['title']);
                $originalSlug = $slug;
                $count = 1;

                while (Post::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }
                $validated['slug'] = $slug;
            }

            // Set published_at if publishing
            if ($validated['status'] === 'published' && empty($validated['published_at'])) {
                $validated['published_at'] = now();
            }

            // Create the post
            $post = Post::create([
                'title' => $validated['title'],
                'slug' => $validated['slug'],
                'excerpt' => $validated['excerpt'],
                'content' => $validated['content'],
                'category_id' => $validated['category_id'],
                'status' => $validated['status'],
                'meta_title' => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'meta_keywords' => $validated['meta_keywords'] ?? null,
                'published_at' => $validated['published_at'],
                'user_id' => Auth::id()
            ]);

            // Handle featured image if uploaded
            if ($request->hasFile('featured_image')) {
                $path = $request->file('featured_image')->store('posts/featured', 'public');
                $post->update(['featured_image' => $path]);
            }

            DB::commit();

            $message = $validated['status'] === 'published'
                ? 'Post published successfully!'
                : 'Post saved as draft successfully!';

            return redirect()
                ->route('admin.posts.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error creating post: ' . $e->getMessage());
        }
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
        $categories = Category::all();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified post
     */
    public function update(Request $request, Post $post)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|max:255',
                'slug' => 'nullable|unique:posts,slug,' . $post->id,
                'excerpt' => 'nullable',
                'content' => 'required',
                'category_id' => 'required|exists:categories,id',
                'status' => 'required|in:draft,published',
                'meta_title' => 'nullable|max:60',
                'meta_description' => 'nullable|max:160',
                'meta_keywords' => 'nullable',
                'published_at' => 'nullable|date',
                'featured_image' => 'nullable|image|max:5120' // 5MB max
            ]);

            DB::beginTransaction();

            // Handle quick actions (status change only)
            if ($request->input('quick_action')) {
                $post->update(['status' => $validated['status']]);
                DB::commit();
                return back()->with('success', 'Post status updated successfully!');
            }

            // Generate or update slug
            if (empty($validated['slug'])) {
                $slug = Str::slug($validated['title']);
                $originalSlug = $slug;
                $count = 1;

                while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }
                $validated['slug'] = $slug;
            }

            // Handle status change and published_at date
            if ($validated['status'] === 'published' && !$post->published_at) {
                $validated['published_at'] = $validated['published_at'] ?? now();
            } elseif ($validated['status'] === 'draft' && $post->status === 'published') {
                $validated['published_at'] = null;
            }

            // Update the post
            $post->update([
                'title' => $validated['title'],
                'slug' => $validated['slug'],
                'excerpt' => $validated['excerpt'],
                'content' => $validated['content'],
                'category_id' => $validated['category_id'],
                'status' => $validated['status'],
                'meta_title' => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'meta_keywords' => $validated['meta_keywords'] ?? null,
                'published_at' => $validated['published_at']
            ]);

            // Handle featured image
            if ($request->hasFile('featured_image')) {
                // Delete old image if exists
                if ($post->featured_image) {
                    Storage::disk('public')->delete($post->featured_image);
                }

                $path = $request->file('featured_image')->store('posts/featured', 'public');
                $post->update(['featured_image' => $path]);
            }

            // Handle image removal
            if ($request->input('remove_image') === '1' && $post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
                $post->update(['featured_image' => null]);
            }

            DB::commit();

            return redirect()
                ->route('admin.posts.index')
                ->with('success', 'Post updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error updating post: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified post
     */
    public function destroy(Post $post)
    {
        try {
            DB::beginTransaction();

            // Delete featured image if exists
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }

            $title = $post->title;
            $post->delete();

            DB::commit();

            return redirect()
                ->route('admin.posts.index')
                ->with('success', "Post '{$title}' was deleted successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting post: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate a post
     */
    public function duplicate(Post $post)
    {
        try {
            DB::beginTransaction();

            $newPost = $post->replicate();
            $newPost->title = "Copy of " . $post->title;
            $newPost->slug = Str::slug($newPost->title);
            $newPost->status = 'draft';
            $newPost->published_at = null;
            $newPost->views = 0;
            $newPost->created_at = now();
            $newPost->updated_at = now();
            $newPost->save();

            if ($post->featured_image) {
                $oldPath = $post->featured_image;
                $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
                $newPath = 'posts/featured/' . uniqid() . '.' . $extension;

                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->copy($oldPath, $newPath);
                    $newPost->featured_image = $newPath;
                    $newPost->save();
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.posts.edit', $newPost)
                ->with('success', 'Post has been duplicated successfully. You are now editing the copy.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error duplicating post: ' . $e->getMessage());
        }
    }

    /**
     * Handle bulk actions on posts
     */
    public function bulk(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'posts' => 'required|array',
                'posts.*' => 'exists:posts,id',
                'action' => 'required|in:delete,publish,draft'
            ]);

            $posts = Post::whereIn('id', $validated['posts']);
            $message = '';

            switch ($validated['action']) {
                case 'delete':
                    // Delete featured images
                    $postsWithImages = $posts->whereNotNull('featured_image')->get();
                    foreach ($postsWithImages as $post) {
                        Storage::disk('public')->delete($post->featured_image);
                    }
                    $posts->delete();
                    $message = 'Selected posts have been deleted';
                    break;

                case 'publish':
                    $posts->update([
                        'status' => 'published',
                        'published_at' => now()
                    ]);
                    $message = 'Selected posts have been published';
                    break;

                case 'draft':
                    $posts->update([
                        'status' => 'draft',
                        'published_at' => null
                    ]);
                    $message = 'Selected posts have been moved to drafts';
                    break;
            }

            DB::commit();

            return redirect()
                ->route('admin.posts.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error performing bulk action: ' . $e->getMessage());
        }
    }
}
