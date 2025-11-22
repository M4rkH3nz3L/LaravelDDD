<?php

namespace App\Console\Commands\Ddd;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ControllerMakeCommand extends Command
{
    protected $signature = 'ddd:controller {context : The bounded context} {name : The controller name} {--type=web : Controller type (web or api)}';

    protected $description = 'Create a new DDD controller';

    public function handle(): int
    {
        $context = $this->argument('context');
        $name = $this->argument('name');
        $type = $this->option('type');

        $path = app_path("Http/Controllers/{$context}/{$name}Controller.php");

        if (File::exists($path)) {
            $this->components->error("Controller [{$name}Controller] already exists!");

            return self::FAILURE;
        }

        File::ensureDirectoryExists(dirname($path));

        $stub = $type === 'api' ? $this->getApiStub() : $this->getWebStub();
        $stub = str_replace(['{{context}}', '{{name}}'], [$context, $name], $stub);

        File::put($path, $stub);

        $this->components->info("Controller [{$name}Controller] created successfully in Http/Controllers/{$context}");

        return self::SUCCESS;
    }

    protected function getWebStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Http\Controllers\{{context}};

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class {{name}}Controller extends Controller
{
    public function __invoke(Request $request)
    {
        // TODO: Implement controller logic
    }
}
PHP;
    }

    protected function getApiStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Http\Controllers\{{context}};

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class {{name}}Controller extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        // TODO: Implement API controller logic

        return response()->json([
            'message' => 'Success',
        ]);
    }
}
PHP;
    }
}
