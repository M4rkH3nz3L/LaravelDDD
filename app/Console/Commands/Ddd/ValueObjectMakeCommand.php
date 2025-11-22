<?php

namespace App\Console\Commands\Ddd;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ValueObjectMakeCommand extends Command
{
    protected $signature = 'ddd:vo {context : The bounded context} {name : The value object name}';

    protected $description = 'Create a new DDD value object';

    public function handle(): int
    {
        $context = $this->argument('context');
        $name = $this->argument('name');

        $path = app_path("Domain/{$context}/ValueObjects/{$name}.php");

        if (File::exists($path)) {
            $this->components->error("Value Object [{$name}] already exists!");

            return self::FAILURE;
        }

        File::ensureDirectoryExists(dirname($path));

        $stub = $this->getStub();
        $stub = str_replace(['{{context}}', '{{name}}'], [$context, $name], $stub);

        File::put($path, $stub);

        $this->components->info("Value Object [{$name}] created successfully in Domain/{$context}/ValueObjects");

        return self::SUCCESS;
    }

    protected function getStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Domain\{{context}}\ValueObjects;

use InvalidArgumentException;

class {{name}}
{
    public function __construct(
        private readonly string $value,
    ) {
        $this->validate($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    private function validate(string $value): void
    {
        if (empty($value)) {
            throw new InvalidArgumentException('{{name}} cannot be empty');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
PHP;
    }
}
