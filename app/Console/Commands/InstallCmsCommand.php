<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallCmsCommand extends Command
{
    protected $signature = 'install:cms {--force : Overwrite existing files}';

    protected $description = 'Install the CMS module with migrations, configs, assets and initial data';

    public function handle(): int
    {
        $this->info('🚀 Installing CMS module...');

        $this->newLine();

        // Copy CMS files from stubs
        $this->copyCmsFiles();

        // Run migrations
        $this->runMigrations();

        // Seeding (optional)
        if ($this->confirm('Do you want to run CMS seeders?', true)) {
            Artisan::call('db:seed', ['--class' => 'CmsSeeder']);
            $this->info('✓ CMS seeders executed.');
        }

        // Cache clear
        Artisan::call('optimize:clear');

        $this->newLine();
        $this->info('🎉 CMS module installed successfully!');
        $this->info('💡 The Article domain is now available in your application.');

        return Command::SUCCESS;
    }

    protected function copyCmsFiles(): void
    {
        $this->info('📁 Copying CMS files...');

        $stubsPath = base_path('stubs/cms');

        if (! File::exists($stubsPath)) {
            $this->error('CMS stub directory not found. Run "php artisan make:cms-stubs" first.');

            return;
        }

        // Copy Domain layer
        $this->copyDirectory(
            "{$stubsPath}/Domain/Article",
            app_path('Domain/Article')
        );

        // Copy Application layer
        $this->copyDirectory(
            "{$stubsPath}/Application/Article",
            app_path('Application/Article')
        );

        // Copy Infrastructure layer
        $this->copyDirectory(
            "{$stubsPath}/Infrastructure/Persistence/Eloquent/Article",
            app_path('Infrastructure/Persistence/Eloquent/Article')
        );

        // Copy migrations
        $this->copyMigrations("{$stubsPath}/database/migrations");

        // Copy seeders
        $this->copyDirectory(
            "{$stubsPath}/database/seeders",
            database_path('seeders')
        );

        $this->info('✓ CMS files copied successfully.');
    }

    protected function copyDirectory(string $source, string $destination): void
    {
        if (! File::exists($source)) {
            return;
        }

        if (File::exists($destination) && ! $this->option('force')) {
            $this->warn("⚠ Skipping {$destination} (already exists)");

            return;
        }

        File::copyDirectory($source, $destination);
    }

    protected function copyMigrations(string $source): void
    {
        if (! File::exists($source)) {
            return;
        }

        $timestamp = date('Y_m_d_His');
        $files = File::files($source);

        foreach ($files as $file) {
            $newName = "{$timestamp}_{$file->getFilename()}";
            File::copy(
                $file->getPathname(),
                database_path("migrations/{$newName}")
            );
        }
    }

    protected function runMigrations(): void
    {
        $this->info('🗄 Running migrations...');

        Artisan::call('migrate', [
            '--force' => true,
        ]);

        $this->info('✓ Migrations completed.');
    }
}
