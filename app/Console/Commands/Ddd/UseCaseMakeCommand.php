<?php

namespace App\Console\Commands\Ddd;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UseCaseMakeCommand extends Command
{
    protected $signature = 'ddd:use-case {context : The bounded context} {name : The use case name}';

    protected $description = 'Create a new DDD use case (Command, Handler, and DTO)';

    public function handle(): int
    {
        $context = $this->argument('context');
        $name = $this->argument('name');

        $this->createCommand($context, $name);
        $this->createHandler($context, $name);
        $this->createDTO($context, $name);

        $this->components->info("Use case [{$name}] created successfully in Application/{$context}");

        return self::SUCCESS;
    }

    protected function createCommand(string $context, string $name): void
    {
        $path = app_path("Application/{$context}/Commands/{$name}Command.php");

        if (File::exists($path)) {
            $this->components->warn("Command already exists: {$path}");

            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $stub = <<<PHP
<?php

namespace App\Application\\{$context}\\Commands;

use App\Application\\{$context}\\DTO\\{$name}DTO;

class {$name}Command
{
    public function __construct(
        public readonly {$name}DTO \$data,
    ) {
    }
}
PHP;

        File::put($path, $stub);
        $this->components->task("Created Command: Application/{$context}/Commands/{$name}Command.php");
    }

    protected function createHandler(string $context, string $name): void
    {
        $path = app_path("Application/{$context}/Handlers/{$name}Handler.php");

        if (File::exists($path)) {
            $this->components->warn("Handler already exists: {$path}");

            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $stub = <<<PHP
<?php

namespace App\Application\\{$context}\\Handlers;

use App\Application\\{$context}\\Commands\\{$name}Command;

class {$name}Handler
{
    public function __construct()
    {
    }

    public function handle({$name}Command \$command): void
    {
        // TODO: Implement use case logic
    }
}
PHP;

        File::put($path, $stub);
        $this->components->task("Created Handler: Application/{$context}/Handlers/{$name}Handler.php");
    }

    protected function createDTO(string $context, string $name): void
    {
        $path = app_path("Application/{$context}/DTO/{$name}DTO.php");

        if (File::exists($path)) {
            $this->components->warn("DTO already exists: {$path}");

            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $stub = <<<PHP
<?php

namespace App\Application\\{$context}\\DTO;

class {$name}DTO
{
    public function __construct(
        // TODO: Add DTO properties
    ) {
    }
}
PHP;

        File::put($path, $stub);
        $this->components->task("Created DTO: Application/{$context}/DTO/{$name}DTO.php");
    }
}
