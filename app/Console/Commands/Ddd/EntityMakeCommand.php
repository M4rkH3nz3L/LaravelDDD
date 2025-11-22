<?php

namespace App\Console\Commands\Ddd;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class EntityMakeCommand extends Command
{
    protected $signature = 'ddd:entity {context : The bounded context} {name : The entity name}';

    protected $description = 'Create a new DDD entity';

    public function handle(): int
    {
        $context = $this->argument('context');
        $name = $this->argument('name');

        $path = app_path("Domain/{$context}/Entities/{$name}.php");

        if (File::exists($path)) {
            $this->components->error("Entity [{$name}] already exists!");

            return self::FAILURE;
        }

        File::ensureDirectoryExists(dirname($path));

        $stub = $this->getStub();
        $stub = str_replace(['{{context}}', '{{name}}'], [$context, $name], $stub);

        File::put($path, $stub);

        $this->components->info("Entity [{$name}] created successfully in Domain/{$context}/Entities");

        return self::SUCCESS;
    }

    protected function getStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Domain\{{context}}\Entities;

class {{name}}
{
    public function __construct(
        private readonly string $id,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }
}
PHP;
    }
}
