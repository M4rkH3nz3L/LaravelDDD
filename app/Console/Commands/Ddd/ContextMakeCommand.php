<?php

namespace App\Console\Commands\Ddd;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ContextMakeCommand extends Command
{
    protected $signature = 'ddd:context {name : The name of the bounded context}';

    protected $description = 'Create a new DDD bounded context structure';

    public function handle(): int
    {
        $contextName = $this->argument('name');

        $this->info("Creating bounded context: {$contextName}");

        $this->createDirectories($contextName);

        $this->components->info("Bounded context [{$contextName}] created successfully.");

        return self::SUCCESS;
    }

    protected function createDirectories(string $contextName): void
    {
        $directories = [
            app_path("Domain/{$contextName}/Entities"),
            app_path("Domain/{$contextName}/ValueObjects"),
            app_path("Domain/{$contextName}/Repositories"),
            app_path("Domain/{$contextName}/Services"),
            app_path("Application/{$contextName}/Commands"),
            app_path("Application/{$contextName}/Queries"),
            app_path("Application/{$contextName}/Handlers"),
            app_path("Application/{$contextName}/DTO"),
            app_path("Infrastructure/Persistence/Eloquent/{$contextName}"),
        ];

        foreach ($directories as $directory) {
            if (! File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true);
                $this->components->task("Created directory: {$directory}");
            }
        }
    }
}
