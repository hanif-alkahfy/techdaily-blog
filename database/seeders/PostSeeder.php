<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have at least one user
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@techdaily.com',
            ]);
        }

        // Get existing users or create some
        $users = User::all();
        if ($users->count() < 3) {
            $users = $users->concat(User::factory(3)->create());
        }

        // Create specific sample posts
        $this->createSpecificPosts($users);

        // Create random posts
        $this->createRandomPosts($users);

        $this->command->info('Created ' . Post::count() . ' posts total');
    }

    /**
     * Create specific sample posts based on planning document
     */
    private function createSpecificPosts($users): void
    {
        $specificPosts = [
            [
                'title' => 'Laravel 10 Features yang Wajib Dicoba Developer',
                'category' => 'Tutorial',
                'status' => 'published',
                'excerpt' => 'Explore the latest features in Laravel 10 that every developer should know about.',
                'content' => 'Laravel 10 membawa berbagai fitur menarik yang dapat meningkatkan produktivitas developer. Mari kita bahas fitur-fitur utama yang wajib dicoba.

Process Interaction: Laravel 10 memperkenalkan Process facade yang memudahkan interaksi dengan sistem operasi. Test Profiling: Fitur baru untuk profiling performance test. Laravel Pennant: Package resmi untuk feature flags dengan mudah toggle fitur production, A/B testing yang lebih baik, dan kontrol deployment yang granular.

Fitur-fitur ini membuat Laravel semakin powerful untuk development modern. Coba implementasikan di project Anda!',
            ],
            [
                'title' => 'Mengapa React Masih Dominan di 2024?',
                'category' => 'Opinion',
                'status' => 'published',
                'excerpt' => 'An analysis of why React continues to be the go-to choice for frontend development.',
                'content' => 'React telah menjadi pilihan utama frontend developer selama bertahun-tahun. Tapi mengapa masih dominan di 2024?

Ekosistem yang Matang: React memiliki ekosistem yang sangat matang dengan berbagai library pendukung seperti Next.js untuk SSR/SSG, React Query untuk state management, Styled Components untuk styling, dan Testing Library untuk testing.

Community Support: Dengan community terbesar di frontend development, React memiliki documentation yang excellent, tutorial dan resource melimpah, job market yang besar, dan active development dari Meta.

Performance Optimization: React 18 membawa concurrent features yang revolutionary. Meskipun ada kompetitor seperti Vue dan Svelte, React tetap unggul dalam enterprise adoption dan long-term support.',
            ],
            [
                'title' => 'Tutorial Setup Docker untuk Laravel Development',
                'category' => 'Tutorial',
                'status' => 'draft',
                'excerpt' => 'Step-by-step guide to setup Docker environment for Laravel development.',
                'content' => 'Docker memudahkan setup environment Laravel yang konsisten across different machines. Berikut tutorial lengkapnya.

Prerequisites: Docker Desktop installed dan basic understanding of Laravel.

Setup Docker Compose: Buat file docker-compose.yml dengan konfigurasi app service, mysql service, dan volume mapping.

Dockerfile: Configure PHP environment dengan extension yang dibutuhkan seperti pdo_mysql, mbstring, exif, pcntl, bcmath, dan gd.

Dengan setup ini, development environment Laravel Anda akan konsisten dan mudah di-share dengan tim.',
            ],
            [
                'title' => 'Review: Top 5 VS Code Extensions untuk PHP Developer',
                'category' => 'Review',
                'status' => 'published',
                'excerpt' => 'Comprehensive review of the best VS Code extensions that boost PHP developer productivity.',
                'content' => 'VS Code adalah editor pilihan mayoritas PHP developer. Berikut 5 extension terbaik untuk boost productivity.

PHP Intelephense: Extension premium yang memberikan intelligent code completion, error detection, go to definition/references, dan code formatting. Rating: 5/5 stars.

Laravel Extension Pack: Bundle extension khusus Laravel termasuk Laravel Blade Snippets, Laravel Artisan, Laravel Extra Intellisense, dan DotENV support. Rating: 5/5 stars.

PHP Debug: Debug PHP applications dengan Xdebug integration. Rating: 4/5 stars.

GitLens: Git integration yang powerful dengan blame annotations, repository insights, commit graph, dan file history. Rating: 5/5 stars.

Thunder Client: REST API client built-in VS Code untuk test API endpoints, environment variables, dan collection management. Rating: 4/5 stars.

Extension-extension ini akan significantly improve your PHP development workflow di VS Code.',
            ],
            [
                'title' => 'Trend Teknologi 2024: AI dan Machine Learning',
                'category' => 'News',
                'status' => 'draft',
                'excerpt' => 'Latest trends in AI and Machine Learning that are shaping the technology landscape in 2024.',
                'content' => '2024 menjadi tahun breakthrough untuk AI dan Machine Learning. Berikut trend yang perlu developer ketahui.

Large Language Models (LLMs): Perkembangan LLMs semakin pesat dengan GPT-4 dan variants, open source alternatives seperti Llama dan Claude, serta specialized models untuk coding.

AI-Powered Development Tools: Developer tools dengan AI integration seperti GitHub Copilot untuk code generation, ChatGPT untuk debugging, AI code reviews, dan automated testing generation.

Edge AI: AI inference moving to edge devices dengan reduced latency, better privacy, offline capabilities, dan mobile AI applications.

MLOps Maturity: Machine Learning Operations semakin mature dengan better model deployment, monitoring dan observability, A/B testing untuk ML models, dan continuous training pipelines.

Ethics dan Regulation: Focus on responsible AI termasuk bias detection and mitigation, explainable AI, privacy preservation, dan regulatory compliance.

Para developer perlu memahami trend ini untuk stay relevant di era AI-first development.',
            ],
        ];

        foreach ($specificPosts as $postData) {
            Post::factory()->create([
                'title' => $postData['title'],
                'category' => $postData['category'],
                'status' => $postData['status'],
                'excerpt' => $postData['excerpt'],
                'content' => $postData['content'],
                'user_id' => $users->random()->id,
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        $this->command->info('Created 5 specific sample posts');
    }

    /**
     * Create additional random posts
     */
    private function createRandomPosts($users): void
    {
        // Create posts by category distribution
        $categoryDistribution = [
            'Tutorial' => 4,
            'Opinion' => 2,
            'Review' => 2,
            'News' => 2,
            'Tips' => 3,
        ];

        foreach ($categoryDistribution as $category => $count) {
            Post::factory($count)
                ->category($category)
                ->create([
                    'user_id' => $users->random()->id,
                ]);
        }

        // Create some published posts specifically
        Post::factory(8)->published()->create([
            'user_id' => $users->random()->id,
        ]);

        // Create some draft posts
        Post::factory(3)->draft()->create([
            'user_id' => $users->random()->id,
        ]);

        $this->command->info('Created additional random posts');
    }
}
