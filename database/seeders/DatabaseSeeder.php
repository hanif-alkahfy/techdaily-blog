<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a default admin user if not exists
        if (!User::where('email', 'admin@techdaily.com')->exists()) {
            User::factory()->create([
                'name' => 'TechDaily Admin',
                'email' => 'admin@techdaily.com',
            ]);
        }

        // Create additional test users
        User::factory(3)->create();

        // Run PostSeeder
        $this->call([
            PostSeeder::class,
        ]);

        $this->command->info('Database seeding completed successfully!');
    }
}
