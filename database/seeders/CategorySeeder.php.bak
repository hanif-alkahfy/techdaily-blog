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
                'name' => 'Web Development',
                'slug' => 'web-development',
                'description' => 'Articles about web development technologies and best practices',
            ],
            [
                'name' => 'Mobile Development',
                'slug' => 'mobile-development',
                'description' => 'Topics covering mobile app development for iOS and Android',
            ],
            [
                'name' => 'DevOps',
                'slug' => 'devops',
                'description' => 'Content about DevOps practices, tools, and methodologies',
            ],
            [
                'name' => 'Artificial Intelligence',
                'slug' => 'artificial-intelligence',
                'description' => 'Exploring AI, Machine Learning, and Data Science',
            ],
            [
                'name' => 'Cybersecurity',
                'slug' => 'cybersecurity',
                'description' => 'Articles about security best practices and latest threats',
            ],
            [
                'name' => 'Cloud Computing',
                'slug' => 'cloud-computing',
                'description' => 'Topics about cloud platforms, services, and architecture',
            ],
            [
                'name' => 'Programming Languages',
                'slug' => 'programming-languages',
                'description' => 'Tutorials and insights about various programming languages',
            ],
            [
                'name' => 'Software Architecture',
                'slug' => 'software-architecture',
                'description' => 'Design patterns, principles, and architectural concepts',
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
