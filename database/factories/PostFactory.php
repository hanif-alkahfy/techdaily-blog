<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(rand(4, 8));
        $categories = ['Tutorial', 'Opinion', 'Review', 'News', 'Tips'];

        return [
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title),
            'excerpt' => fake()->paragraph(2),
            'content' => $this->generateContent(),
            'category' => fake()->randomElement($categories),
            'status' => fake()->randomElement(['draft', 'published']),
            'user_id' => User::factory(),
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'updated_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Generate realistic blog content
     */
    private function generateContent(): string
    {
        $paragraphs = [];

        // Introduction paragraph
        $paragraphs[] = fake()->paragraph(4);

        // Main content (2-4 paragraphs)
        $mainParagraphs = rand(2, 4);
        for ($i = 0; $i < $mainParagraphs; $i++) {
            $paragraphs[] = fake()->paragraph(rand(3, 6));
        }

        // Add some technical elements randomly
        if (rand(1, 3) === 1) {
            $paragraphs[] = $this->generateCodeBlock();
        }

        // Add lists occasionally
        if (rand(1, 4) === 1) {
            $paragraphs[] = $this->generateList();
        }

        // Conclusion paragraph
        $paragraphs[] = fake()->paragraph(3);

        return implode("\n\n", $paragraphs);
    }

    /**
     * Generate fake code block
     */
    private function generateCodeBlock(): string
    {
        $codeBlocks = [
            "```php\n<?php\n\nclass Example {\n    public function hello() {\n        return 'Hello World!';\n    }\n}\n```",
            "```javascript\nconst greeting = () => {\n    console.log('Hello, World!');\n};\n\ngreeting();\n```",
            "```bash\n# Install dependencies\nnpm install\n\n# Run development server\nnpm run dev\n```",
            "```css\n.container {\n    max-width: 1200px;\n    margin: 0 auto;\n    padding: 20px;\n}\n```"
        ];

        return fake()->randomElement($codeBlocks);
    }

    /**
     * Generate fake list
     */
    private function generateList(): string
    {
        $items = [];
        $itemCount = rand(3, 6);

        for ($i = 0; $i < $itemCount; $i++) {
            $items[] = "- " . fake()->sentence(rand(3, 8));
        }

        return implode("\n", $items);
    }

    /**
     * Create published posts
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }

    /**
     * Create draft posts
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    /**
     * Create posts for specific category
     */
    public function category(string $category): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => $category,
        ]);
    }

    /**
     * Create posts for specific user
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}
