<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:project';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the project with all necessary configurations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting project setup...');        // Create storage link
        if (!file_exists(public_path('storage'))) {
            $this->info('Creating storage link...');
            $this->call('storage:link');
        } else {
            $this->info('Storage link already exists.');
        }

        // Create necessary storage directories
        $directories = [
            storage_path('app/public/posts'),
            storage_path('app/public/users'),
            storage_path('app/public/categories'),
        ];

        foreach ($directories as $directory) {
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
                $this->info("Created directory: {$directory}");
            }
        }

        // Run migrations fresh with seeding
        if ($this->confirm('Would you like to refresh the database and run all seeders?', true)) {
            $this->info('Refreshing database and running seeders...');
            $this->call('migrate:fresh', ['--seed' => true]);
        }

        $this->info('Project setup completed successfully!');
        $this->info('You can now start using your application.');
    }
}
