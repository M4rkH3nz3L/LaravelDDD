<?php

use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Clean up stubs directory before each test
    if (File::exists(base_path('stubs/cms'))) {
        File::deleteDirectory(base_path('stubs/cms'));
    }
});

afterEach(function () {
    // Clean up after tests
    if (File::exists(base_path('stubs/cms'))) {
        File::deleteDirectory(base_path('stubs/cms'));
    }
});

it('creates cms stub files successfully', function () {
    $this->artisan('make:cms-stubs')
        ->expectsOutput('📦 Generating CMS stub files...')
        ->expectsOutput('✓ Created stubs directory')
        ->expectsOutput('✅ CMS stub files generated successfully!')
        ->assertSuccessful();

    expect(File::exists(base_path('stubs/cms')))->toBeTrue();
});

it('creates domain layer stubs', function () {
    $this->artisan('make:cms-stubs')->assertSuccessful();

    $basePath = base_path('stubs/cms');

    expect(File::exists("{$basePath}/Domain/Article/Entities/Article.php"))->toBeTrue();
    expect(File::exists("{$basePath}/Domain/Article/ValueObjects/ArticleId.php"))->toBeTrue();
    expect(File::exists("{$basePath}/Domain/Article/ValueObjects/Slug.php"))->toBeTrue();
    expect(File::exists("{$basePath}/Domain/Article/Repositories/ArticleRepositoryInterface.php"))->toBeTrue();
});

it('creates application layer stubs', function () {
    $this->artisan('make:cms-stubs')->assertSuccessful();

    $basePath = base_path('stubs/cms/Application/Article');

    // Commands
    expect(File::exists("{$basePath}/Commands/CreateArticleCommand.php"))->toBeTrue();
    expect(File::exists("{$basePath}/Commands/PublishArticleCommand.php"))->toBeTrue();

    // Queries
    expect(File::exists("{$basePath}/Queries/GetArticleByIdQuery.php"))->toBeTrue();
    expect(File::exists("{$basePath}/Queries/GetPublishedArticlesQuery.php"))->toBeTrue();

    // Handlers
    expect(File::exists("{$basePath}/Handlers/CreateArticleHandler.php"))->toBeTrue();
    expect(File::exists("{$basePath}/Handlers/PublishArticleHandler.php"))->toBeTrue();
    expect(File::exists("{$basePath}/Handlers/GetArticleByIdHandler.php"))->toBeTrue();
    expect(File::exists("{$basePath}/Handlers/GetPublishedArticlesHandler.php"))->toBeTrue();

    // DTOs
    expect(File::exists("{$basePath}/DTO/ArticleDTO.php"))->toBeTrue();
});

it('creates infrastructure layer stubs', function () {
    $this->artisan('make:cms-stubs')->assertSuccessful();

    $basePath = base_path('stubs/cms/Infrastructure/Persistence/Eloquent/Article');

    expect(File::exists("{$basePath}/ArticleModel.php"))->toBeTrue();
    expect(File::exists("{$basePath}/ArticleRepositoryEloquent.php"))->toBeTrue();
});

it('creates migration stubs', function () {
    $this->artisan('make:cms-stubs')->assertSuccessful();

    $basePath = base_path('stubs/cms/database/migrations');
    $files = File::files($basePath);

    expect(count($files))->toBe(1);
    expect($files[0]->getFilename())->toContain('create_articles_table.php');
});

it('creates seeder stubs', function () {
    $this->artisan('make:cms-stubs')->assertSuccessful();

    $basePath = base_path('stubs/cms/database/seeders');

    expect(File::exists("{$basePath}/CmsSeeder.php"))->toBeTrue();
});

it('prompts for confirmation when directory exists', function () {
    // Create stubs first
    $this->artisan('make:cms-stubs')->assertSuccessful();

    // Try to create again without force
    $this->artisan('make:cms-stubs')
        ->expectsConfirmation('CMS stubs directory already exists. Overwrite?', 'no')
        ->expectsOutput('✗ Operation cancelled.')
        ->assertFailed();
});

it('overwrites existing stubs with force option', function () {
    // Create stubs first
    $this->artisan('make:cms-stubs')->assertSuccessful();

    // Create again with force
    $this->artisan('make:cms-stubs --force')
        ->expectsOutput('✅ CMS stub files generated successfully!')
        ->assertSuccessful();

    expect(File::exists(base_path('stubs/cms')))->toBeTrue();
});

it('generated stub files contain valid php code', function () {
    $this->artisan('make:cms-stubs')->assertSuccessful();

    $articleEntity = File::get(base_path('stubs/cms/Domain/Article/Entities/Article.php'));

    expect($articleEntity)->toContain('namespace App\Domain\Article\Entities');
    expect($articleEntity)->toContain('final class Article');
    expect($articleEntity)->toContain('public function publish(): void');
});
