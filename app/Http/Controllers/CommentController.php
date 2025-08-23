<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    /**
     * Tampilkan semua komentar untuk sebuah post.
     */
    public function index(Post $post)
    {
        $comments = $post->comments()->with('user')->latest()->get();
        return view('comments.index', compact('post', 'comments'));
    }

    /**
     * Simpan komentar baru.
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    /**
     * Tampilkan form edit komentar.
     */
    public function edit(Comment $comment)
    {
        // hanya pemilik atau admin bisa edit
        $this->authorize('update', $comment);

        return view('comments.edit', compact('comment'));
    }

    /**
     * Update komentar.
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $comment->update([
            'body' => $request->body,
        ]);

        return redirect()->back()->with('success', 'Comment updated successfully!');
    }

    /**
     * Hapus komentar.
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }
}
