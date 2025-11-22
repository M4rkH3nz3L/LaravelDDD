<?php

namespace App\Console\Commands\Ddd;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class EloquentModelMakeCommand extends Command
{
    protected $signature = 'ddd:eloquent-model {context : The bounded context} {name : The model name}';

    protected $description = 'Create a new Eloquent model in Infrastructure layer';

    public function handle(): int
    {
        $context = $this->argument('context');
        $name = $this->argument('name');

        $this->createModel($context, $name);
        $this->createRepository($context, $name);

        $this->components->info("Eloquent model [{$name}Model] and repository created successfully");

        return self::SUCCESS;
    }

    protected function createModel(string $context, string $name): void
    {
        $path = app_path("Infrastructure/Persistence/Eloquent/{$context}/{$name}Model.php");

        if (File::exists($path)) {
            $this->components->warn("Model already exists: {$path}");

            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $stub = <<<PHP
<?php

namespace App\Infrastructure\Persistence\Eloquent\\{$context};

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {$name}Model extends Model
{
    use HasFactory;

    protected \$table = '{$this->getTableName($name)}';

    protected \$fillable = [
        // TODO: Add fillable fields
    ];

    protected function casts(): array
    {
        return [
            // TODO: Add casts
        ];
    }
}
PHP;

        File::put($path, $stub);
        $this->components->task("Created Model: Infrastructure/Persistence/Eloquent/{$context}/{$name}Model.php");
    }

    protected function createRepository(string $context, string $name): void
    {
        $path = app_path("Infrastructure/Persistence/Eloquent/{$context}/{$name}RepositoryEloquent.php");

        if (File::exists($path)) {
            $this->components->warn("Repository already exists: {$path}");

            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $stub = <<<PHP
<?php

namespace App\Infrastructure\Persistence\Eloquent\\{$context};

class {$name}RepositoryEloquent
{
    public function __construct(
        private readonly {$name}Model \$model,
    ) {
    }

    public function find(string \$id): ?{$name}Model
    {
        return \$this->model->find(\$id);
    }

    public function all(): array
    {
        return \$this->model->all()->toArray();
    }

    public function save({$name}Model \$model): {$name}Model
    {
        \$model->save();

        return \$model;
    }

    public function delete(string \$id): bool
    {
        return \$this->model->destroy(\$id) > 0;
    }
}
PHP;

        File::put($path, $stub);
        $this->components->task("Created Repository: Infrastructure/Persistence/Eloquent/{$context}/{$name}RepositoryEloquent.php");
    }

    protected function getTableName(string $name): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name)).'s';
    }
}
