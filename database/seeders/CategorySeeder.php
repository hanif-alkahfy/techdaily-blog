<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Tutorial',
                'slug' => 'tutorial',
                'description' => 'Step-by-step guides and tutorials on various programming topics and technologies',
            ],
            [
                'name' => 'Opinion',
                'slug' => 'opinion',
                'description' => 'Personal views and insights on technology trends and practices',
            ],
            [
                'name' => 'Review',
                'slug' => 'review',
                'description' => 'In-depth reviews of tools, technologies, and frameworks',
            ],
            [
                'name' => 'News',
                'slug' => 'news',
                'description' => 'Latest updates and announcements in the tech world',
            ],
            [
                'name' => 'Tips',
                'slug' => 'tips',
                'description' => 'Quick tips and best practices for developers',
            ]
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'description' => $category['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
