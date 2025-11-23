<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeCmsStubsCommand extends Command
{
    protected $signature = 'make:cms-stubs {--force : Overwrite existing stub files}';

    protected $description = 'Generate CMS module stub files for installation';

    public function handle(): int
    {
        $this->info('📦 Generating CMS stub files...');
        $this->newLine();

        $stubsPath = base_path('stubs/cms');

        if (File::exists($stubsPath) && ! $this->option('force')) {
            if (! $this->confirm('CMS stubs directory already exists. Overwrite?', false)) {
                $this->warn('✗ Operation cancelled.');

                return Command::FAILURE;
            }
        }

        $this->createStubsDirectory($stubsPath);
        $this->createDomainStubs($stubsPath);
        $this->createApplicationStubs($stubsPath);
        $this->createInfrastructureStubs($stubsPath);
        $this->createMigrationStubs($stubsPath);
        $this->createSeederStubs($stubsPath);

        $this->newLine();
        $this->info('✅ CMS stub files generated successfully!');
        $this->info('💡 Run "php artisan install:cms" to install the CMS module.');

        return Command::SUCCESS;
    }

    protected function createStubsDirectory(string $path): void
    {
        if (File::exists($path)) {
            File::deleteDirectory($path);
        }

        File::makeDirectory($path, 0755, true);
        $this->info('✓ Created stubs directory');
    }

    protected function createDomainStubs(string $basePath): void
    {
        $this->info('📁 Creating Domain layer stubs...');

        // Article Entity
        $entityPath = "{$basePath}/Domain/Article/Entities";
        File::ensureDirectoryExists($entityPath);
        File::put("{$entityPath}/Article.php", $this->getArticleEntityStub());

        // Value Objects
        $voPath = "{$basePath}/Domain/Article/ValueObjects";
        File::ensureDirectoryExists($voPath);
        File::put("{$voPath}/ArticleId.php", $this->getArticleIdStub());
        File::put("{$voPath}/Slug.php", $this->getSlugStub());

        // Repository Interface
        $repoPath = "{$basePath}/Domain/Article/Repositories";
        File::ensureDirectoryExists($repoPath);
        File::put("{$repoPath}/ArticleRepositoryInterface.php", $this->getArticleRepositoryInterfaceStub());

        $this->info('  ✓ Domain entities, value objects, and repository interface');
    }

    protected function createApplicationStubs(string $basePath): void
    {
        $this->info('📁 Creating Application layer stubs...');

        // Commands
        $commandPath = "{$basePath}/Application/Article/Commands";
        File::ensureDirectoryExists($commandPath);
        File::put("{$commandPath}/CreateArticleCommand.php", $this->getCreateArticleCommandStub());
        File::put("{$commandPath}/PublishArticleCommand.php", $this->getPublishArticleCommandStub());

        // Queries
        $queryPath = "{$basePath}/Application/Article/Queries";
        File::ensureDirectoryExists($queryPath);
        File::put("{$queryPath}/GetArticleByIdQuery.php", $this->getGetArticleByIdQueryStub());
        File::put("{$queryPath}/GetPublishedArticlesQuery.php", $this->getGetPublishedArticlesQueryStub());

        // Handlers
        $handlerPath = "{$basePath}/Application/Article/Handlers";
        File::ensureDirectoryExists($handlerPath);
        File::put("{$handlerPath}/CreateArticleHandler.php", $this->getCreateArticleHandlerStub());
        File::put("{$handlerPath}/PublishArticleHandler.php", $this->getPublishArticleHandlerStub());
        File::put("{$handlerPath}/GetArticleByIdHandler.php", $this->getGetArticleByIdHandlerStub());
        File::put("{$handlerPath}/GetPublishedArticlesHandler.php", $this->getGetPublishedArticlesHandlerStub());

        // DTOs
        $dtoPath = "{$basePath}/Application/Article/DTO";
        File::ensureDirectoryExists($dtoPath);
        File::put("{$dtoPath}/ArticleDTO.php", $this->getArticleDTOStub());

        $this->info('  ✓ Commands, queries, handlers, and DTOs');
    }

    protected function createInfrastructureStubs(string $basePath): void
    {
        $this->info('📁 Creating Infrastructure layer stubs...');

        $infraPath = "{$basePath}/Infrastructure/Persistence/Eloquent/Article";
        File::ensureDirectoryExists($infraPath);

        File::put("{$infraPath}/ArticleModel.php", $this->getArticleModelStub());
        File::put("{$infraPath}/ArticleRepositoryEloquent.php", $this->getArticleRepositoryEloquentStub());

        $this->info('  ✓ Eloquent model and repository implementation');
    }

    protected function createMigrationStubs(string $basePath): void
    {
        $this->info('📁 Creating migration stubs...');

        $migrationPath = "{$basePath}/database/migrations";
        File::ensureDirectoryExists($migrationPath);

        $timestamp = date('Y_m_d_His');
        File::put(
            "{$migrationPath}/{$timestamp}_create_articles_table.php",
            $this->getArticlesMigrationStub()
        );

        $this->info('  ✓ Database migrations');
    }

    protected function createSeederStubs(string $basePath): void
    {
        $this->info('📁 Creating seeder stubs...');

        $seederPath = "{$basePath}/database/seeders";
        File::ensureDirectoryExists($seederPath);

        File::put("{$seederPath}/CmsSeeder.php", $this->getCmsSeederStub());

        $this->info('  ✓ Database seeders');
    }

    protected function getArticleEntityStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Domain\Article\Entities;

use App\Domain\Article\ValueObjects\ArticleId;
use App\Domain\Article\ValueObjects\Slug;

final class Article
{
    public function __construct(
        private ArticleId $id,
        private string $title,
        private string $content,
        private Slug $slug,
        private bool $isPublished = false,
        private ?\DateTimeImmutable $publishedAt = null
    ) {
    }

    public function publish(): void
    {
        if ($this->isPublished) {
            throw new \DomainException('Article is already published');
        }

        $this->isPublished = true;
        $this->publishedAt = new \DateTimeImmutable();
    }

    public function unpublish(): void
    {
        $this->isPublished = false;
        $this->publishedAt = null;
    }

    public function updateContent(string $title, string $content): void
    {
        $this->title = $title;
        $this->content = $content;
    }

    public function id(): ArticleId
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function slug(): Slug
    {
        return $this->slug;
    }

    public function isPublished(): bool
    {
        return $this->isPublished;
    }

    public function publishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }
}

PHP;
    }

    protected function getArticleIdStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Domain\Article\ValueObjects;

use Ramsey\Uuid\Uuid;

final readonly class ArticleId
{
    public function __construct(private string $value)
    {
        if (! Uuid::isValid($value)) {
            throw new \InvalidArgumentException('Invalid Article ID format');
        }
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(ArticleId $other): bool
    {
        return $this->value === $other->value;
    }
}

PHP;
    }

    protected function getSlugStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Domain\Article\ValueObjects;

use Illuminate\Support\Str;

final readonly class Slug
{
    public function __construct(private string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Slug cannot be empty');
        }

        if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value)) {
            throw new \InvalidArgumentException('Invalid slug format');
        }
    }

    public static function fromString(string $value): self
    {
        return new self(Str::slug($value));
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Slug $other): bool
    {
        return $this->value === $other->value;
    }
}

PHP;
    }

    protected function getArticleRepositoryInterfaceStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Domain\Article\Repositories;

use App\Domain\Article\Entities\Article;
use App\Domain\Article\ValueObjects\ArticleId;

interface ArticleRepositoryInterface
{
    public function save(Article $article): void;

    public function findById(ArticleId $id): ?Article;

    public function findPublished(): array;

    public function delete(ArticleId $id): void;
}

PHP;
    }

    protected function getCreateArticleCommandStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Application\Article\Commands;

final readonly class CreateArticleCommand
{
    public function __construct(
        public string $title,
        public string $content,
        public string $slug
    ) {
    }
}

PHP;
    }

    protected function getPublishArticleCommandStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Application\Article\Commands;

final readonly class PublishArticleCommand
{
    public function __construct(public string $articleId)
    {
    }
}

PHP;
    }

    protected function getGetArticleByIdQueryStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Application\Article\Queries;

final readonly class GetArticleByIdQuery
{
    public function __construct(public string $articleId)
    {
    }
}

PHP;
    }

    protected function getGetPublishedArticlesQueryStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Application\Article\Queries;

final readonly class GetPublishedArticlesQuery
{
    public function __construct()
    {
    }
}

PHP;
    }

    protected function getCreateArticleHandlerStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Application\Article\Handlers;

use App\Application\Article\Commands\CreateArticleCommand;
use App\Application\Article\DTO\ArticleDTO;
use App\Domain\Article\Entities\Article;
use App\Domain\Article\Repositories\ArticleRepositoryInterface;
use App\Domain\Article\ValueObjects\ArticleId;
use App\Domain\Article\ValueObjects\Slug;

final readonly class CreateArticleHandler
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) {
    }

    public function handle(CreateArticleCommand $command): ArticleDTO
    {
        $article = new Article(
            id: ArticleId::generate(),
            title: $command->title,
            content: $command->content,
            slug: Slug::fromString($command->slug)
        );

        $this->articleRepository->save($article);

        return new ArticleDTO(
            id: $article->id()->value(),
            title: $article->title(),
            content: $article->content(),
            slug: $article->slug()->value(),
            isPublished: $article->isPublished(),
            publishedAt: $article->publishedAt()
        );
    }
}

PHP;
    }

    protected function getPublishArticleHandlerStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Application\Article\Handlers;

use App\Application\Article\Commands\PublishArticleCommand;
use App\Domain\Article\Repositories\ArticleRepositoryInterface;
use App\Domain\Article\ValueObjects\ArticleId;

final readonly class PublishArticleHandler
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) {
    }

    public function handle(PublishArticleCommand $command): void
    {
        $articleId = ArticleId::fromString($command->articleId);
        $article = $this->articleRepository->findById($articleId);

        if (! $article) {
            throw new \DomainException('Article not found');
        }

        $article->publish();
        $this->articleRepository->save($article);
    }
}

PHP;
    }

    protected function getGetArticleByIdHandlerStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Application\Article\Handlers;

use App\Application\Article\DTO\ArticleDTO;
use App\Application\Article\Queries\GetArticleByIdQuery;
use App\Domain\Article\Repositories\ArticleRepositoryInterface;
use App\Domain\Article\ValueObjects\ArticleId;

final readonly class GetArticleByIdHandler
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) {
    }

    public function handle(GetArticleByIdQuery $query): ?ArticleDTO
    {
        $articleId = ArticleId::fromString($query->articleId);
        $article = $this->articleRepository->findById($articleId);

        if (! $article) {
            return null;
        }

        return new ArticleDTO(
            id: $article->id()->value(),
            title: $article->title(),
            content: $article->content(),
            slug: $article->slug()->value(),
            isPublished: $article->isPublished(),
            publishedAt: $article->publishedAt()
        );
    }
}

PHP;
    }

    protected function getGetPublishedArticlesHandlerStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Application\Article\Handlers;

use App\Application\Article\DTO\ArticleDTO;
use App\Application\Article\Queries\GetPublishedArticlesQuery;
use App\Domain\Article\Repositories\ArticleRepositoryInterface;

final readonly class GetPublishedArticlesHandler
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) {
    }

    public function handle(GetPublishedArticlesQuery $query): array
    {
        $articles = $this->articleRepository->findPublished();

        return array_map(
            fn ($article) => new ArticleDTO(
                id: $article->id()->value(),
                title: $article->title(),
                content: $article->content(),
                slug: $article->slug()->value(),
                isPublished: $article->isPublished(),
                publishedAt: $article->publishedAt()
            ),
            $articles
        );
    }
}

PHP;
    }

    protected function getArticleDTOStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Application\Article\DTO;

final readonly class ArticleDTO
{
    public function __construct(
        public string $id,
        public string $title,
        public string $content,
        public string $slug,
        public bool $isPublished,
        public ?\DateTimeImmutable $publishedAt
    ) {
    }
}

PHP;
    }

    protected function getArticleModelStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Infrastructure\Persistence\Eloquent\Article;

use Illuminate\Database\Eloquent\Model;

class ArticleModel extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'id',
        'title',
        'content',
        'slug',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public $incrementing = false;

    protected $keyType = 'string';
}

PHP;
    }

    protected function getArticleRepositoryEloquentStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Infrastructure\Persistence\Eloquent\Article;

use App\Domain\Article\Entities\Article;
use App\Domain\Article\Repositories\ArticleRepositoryInterface;
use App\Domain\Article\ValueObjects\ArticleId;
use App\Domain\Article\ValueObjects\Slug;

class ArticleRepositoryEloquent implements ArticleRepositoryInterface
{
    public function save(Article $article): void
    {
        ArticleModel::query()->updateOrCreate(
            ['id' => $article->id()->value()],
            [
                'title' => $article->title(),
                'content' => $article->content(),
                'slug' => $article->slug()->value(),
                'is_published' => $article->isPublished(),
                'published_at' => $article->publishedAt(),
            ]
        );
    }

    public function findById(ArticleId $id): ?Article
    {
        $model = ArticleModel::query()->find($id->value());

        if (! $model) {
            return null;
        }

        return $this->toDomain($model);
    }

    public function findPublished(): array
    {
        return ArticleModel::query()
            ->where('is_published', true)
            ->get()
            ->map(fn ($model) => $this->toDomain($model))
            ->all();
    }

    public function delete(ArticleId $id): void
    {
        ArticleModel::query()->where('id', $id->value())->delete();
    }

    private function toDomain(ArticleModel $model): Article
    {
        return new Article(
            id: ArticleId::fromString($model->id),
            title: $model->title,
            content: $model->content,
            slug: Slug::fromString($model->slug),
            isPublished: $model->is_published,
            publishedAt: $model->published_at ? \DateTimeImmutable::createFromMutable($model->published_at) : null
        );
    }
}

PHP;
    }

    protected function getArticlesMigrationStub(): string
    {
        return <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('content');
            $table->string('slug')->unique();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

PHP;
    }

    protected function getCmsSeederStub(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Eloquent\Article\ArticleModel;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'id' => Uuid::uuid4()->toString(),
                'title' => 'Welcome to Our CMS',
                'content' => 'This is your first article in the CMS system. You can edit or delete it.',
                'slug' => 'welcome-to-our-cms',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'id' => Uuid::uuid4()->toString(),
                'title' => 'Getting Started with DDD',
                'content' => 'Domain-Driven Design helps build maintainable applications with clear boundaries.',
                'slug' => 'getting-started-with-ddd',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'id' => Uuid::uuid4()->toString(),
                'title' => 'Draft Article',
                'content' => 'This article is still being written and not yet published.',
                'slug' => 'draft-article',
                'is_published' => false,
                'published_at' => null,
            ],
        ];

        foreach ($articles as $article) {
            ArticleModel::query()->create($article);
        }
    }
}

PHP;
    }
}
