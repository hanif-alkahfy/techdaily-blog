<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');

        // Create admin user
        $this->command->info('👤 Creating admin user...');
        $admin = User::create([
            'name' => 'Admin TechDaily',
            'email' => 'admin@techdaily.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        // Create some additional users
        $this->command->info('👥 Creating additional users...');
        $users = User::factory(5)->create();
        $allUsers = collect([$admin])->merge($users);

        // Seed categories
        $this->command->info('📁 Seeding categories...');
        $this->call(CategorySeeder::class);

        // Get all categories
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->error('❌ No categories found! Please run CategorySeeder first.');
            return;
        }

        // Create posts with existing users and categories
        $this->command->info('📝 Creating blog posts...');

        // Create some posts by admin
        Post::factory(10)
            ->state([
                'user_id' => $admin->id,
                'category_id' => $categories->random()->id
            ])
            ->create();

        // Create posts by other users
        foreach ($users as $user) {
            Post::factory(rand(3, 8))
                ->state([
                    'user_id' => $user->id,
                    'category_id' => $categories->random()->id
                ])
                ->create();
        }

        // Create some draft posts
        $this->command->info('📄 Creating draft posts...');
        Post::factory(15)
            ->draft()
            ->state([
                'user_id' => $allUsers->random()->id,
                'category_id' => $categories->random()->id
            ])
            ->create();

        // Display summary
        $this->command->info('');
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('📊 Summary:');
        $this->command->info('   👤 Users: ' . User::count());
        $this->command->info('   📁 Categories: ' . Category::count());
        $this->command->info('   📝 Total Posts: ' . Post::count());
        $this->command->info('   📋 Published Posts: ' . Post::published()->count());
        $this->command->info('   📄 Draft Posts: ' . Post::draft()->count());
        $this->command->info('');
        $this->command->info('🔐 Admin Login:');
        $this->command->info('   Email: admin@techdaily.com');
        $this->command->info('   Password: password');
    }
}
