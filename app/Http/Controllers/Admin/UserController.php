<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::withCount('posts')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
            'is_admin' => ['boolean'],
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Set default role if not provided
        $validated['is_admin'] = $validated['is_admin'] ?? false;

        User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => ['nullable', Password::defaults()],
            'is_admin' => ['boolean'],
        ]);

        // Only update password if provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Set admin status if provided
        $validated['is_admin'] = $validated['is_admin'] ?? $user->is_admin;

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Request $request, User $user)
    {
        // Prevent self-deletion
        $currentUser = $request->user();
        if ($currentUser && $user->id === $currentUser->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Optional: Handle user's posts before deletion
        // $user->posts()->delete(); // Delete all posts
        // or
        // $user->posts()->update(['user_id' => 1]); // Transfer to admin

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}
