<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
    }

    public function test_can_get_published_posts(): void
    {
        // Create published and draft posts
        Post::factory()->count(3)->published()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);
        Post::factory()->count(2)->draft()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'slug',
                        'excerpt',
                        'content',
                        'featured_image',
                        'status',
                        'published_at',
                        'created_at',
                        'updated_at',
                        'category' => ['id', 'name', 'slug'],
                        'author' => ['id', 'name'],
                        'meta' => ['title', 'description', 'keywords'],
                    ]
                ],
                'links',
                'meta'
            ]);
    }

    public function test_can_filter_posts_by_category(): void
    {
        // Create posts in different categories
        $category2 = Category::factory()->create(['name' => 'Tutorials', 'slug' => 'tutorials']);

        Post::factory()->count(2)->published()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);
        Post::factory()->count(3)->published()->create([
            'user_id' => $this->user->id,
            'category_id' => $category2->id,
        ]);

        $response = $this->getJson('/api/posts?category=tutorials');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_get_single_post(): void
    {
        $post = Post::factory()->published()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->getJson("/api/posts/{$post->slug}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'slug',
                    'excerpt',
                    'content',
                    'featured_image',
                    'status',
                    'published_at',
                    'created_at',
                    'updated_at',
                    'category' => ['id', 'name', 'slug'],
                    'author' => ['id', 'name'],
                    'meta' => ['title', 'description', 'keywords'],
                ]
            ]);
    }

    public function test_returns_404_for_nonexistent_post(): void
    {
        $response = $this->getJson('/api/posts/nonexistent-post');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Post not found']);
    }

    public function test_draft_posts_are_not_accessible(): void
    {
        $post = Post::factory()->draft()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->getJson("/api/posts/{$post->slug}");

        $response->assertStatus(404);
    }
}
