<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'Technology',
            'Lifestyle',
            'Travel',
            'Food & Cooking',
            'Health & Fitness',
            'Business',
            'Education',
            'Entertainment',
            'Sports',
            'Fashion',
            'Photography',
            'Music',
            'Art & Design',
            'Politics',
            'Science',
            'Gaming',
            'Books',
            'Movies',
            'Nature',
            'Finance'
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(10),
            'color' => $this->faker->randomElement([
                '#007bff', // Blue
                '#6c757d', // Gray
                '#28a745', // Green
                '#dc3545', // Red
                '#ffc107', // Yellow
                '#17a2b8', // Cyan
                '#6f42c1', // Purple
                '#e83e8c', // Pink
                '#20c997', // Teal
                '#fd7e14', // Orange
            ]),
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the category is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the category is featured (low sort order).
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'sort_order' => $this->faker->numberBetween(1, 10),
        ]);
    }
}
