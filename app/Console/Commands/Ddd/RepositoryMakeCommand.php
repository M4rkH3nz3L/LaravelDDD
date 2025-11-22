<?php

namespace App\Console\Commands\Ddd;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RepositoryMakeCommand extends Command
{
    protected $signature = 'ddd:repository {context : The bounded context} {name : The repository name}';

    protected $description = 'Create a new DDD repository interface';

    public function handle(): int
    {
        $context = $this->argument('context');
        $name = $this->argument('name');

        $path = app_path("Domain/{$context}/Repositories/{$name}RepositoryInterface.php");

        if (File::exists($path)) {
            $this->components->error("Repository interface [{$name}RepositoryInterface] already exists!");

            return self::FAILURE;
        }

        File::ensureDirectoryExists(dirname($path));

        $stub = $this->getStub();
        $stub = str_replace(['{{context}}', '{{name}}'], [$context, $name], $stub);

        File::put($path, $stub);

        $this->components->info("Repository interface [{$name}RepositoryInterface] created successfully");

        return self::SUCCESS;
    }

    protected function getStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Domain\{{context}}\Repositories;

use App\Domain\Shared\Contracts\RepositoryInterface;

interface {{name}}RepositoryInterface extends RepositoryInterface
{
    // Add context-specific repository methods here
}
PHP;
    }
}
