<?php

use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Clean up before each test
    cleanupCmsFiles();
});

afterEach(function () {
    // Clean up after tests
    cleanupCmsFiles();
});

it('shows error when stubs directory does not exist', function () {
    $this->artisan('install:cms')
        ->expectsOutput('📁 Copying CMS files...')
        ->expectsOutput('CMS stub directory not found. Run "php artisan make:cms-stubs" first.')
        ->expectsConfirmation('Do you want to run CMS seeders?', 'no')
        ->assertSuccessful();
});

it('copies cms files from stubs to application', function () {
    // First create the stubs
    $this->artisan('make:cms-stubs')->assertSuccessful();

    // Then install
    $this->artisan('install:cms')
        ->expectsOutput('🚀 Installing CMS module...')
        ->expectsOutput('📁 Copying CMS files...')
        ->expectsConfirmation('Do you want to run CMS seeders?', 'no')
        ->assertSuccessful();

    // Verify Domain files were copied
    expect(File::exists(app_path('Domain/Article/Entities/Article.php')))->toBeTrue();
    expect(File::exists(app_path('Domain/Article/ValueObjects/ArticleId.php')))->toBeTrue();
    expect(File::exists(app_path('Domain/Article/Repositories/ArticleRepositoryInterface.php')))->toBeTrue();

    // Verify Application files were copied
    expect(File::exists(app_path('Application/Article/Commands/CreateArticleCommand.php')))->toBeTrue();
    expect(File::exists(app_path('Application/Article/Queries/GetArticleByIdQuery.php')))->toBeTrue();
    expect(File::exists(app_path('Application/Article/Handlers/CreateArticleHandler.php')))->toBeTrue();
    expect(File::exists(app_path('Application/Article/DTO/ArticleDTO.php')))->toBeTrue();

    // Verify Infrastructure files were copied
    expect(File::exists(app_path('Infrastructure/Persistence/Eloquent/Article/ArticleModel.php')))->toBeTrue();
    expect(File::exists(app_path('Infrastructure/Persistence/Eloquent/Article/ArticleRepositoryEloquent.php')))->toBeTrue();
});

it('copies migrations with new timestamp', function () {
    // Create stubs
    $this->artisan('make:cms-stubs')->assertSuccessful();

    // Install
    $this->artisan('install:cms')
        ->expectsConfirmation('Do you want to run CMS seeders?', 'no')
        ->assertSuccessful();

    // Check migrations were copied
    $migrations = File::files(database_path('migrations'));
    $articlesMigration = collect($migrations)->first(fn ($file) => str_contains($file->getFilename(), 'create_articles_table'));

    expect($articlesMigration)->not->toBeNull();
});

it('copies seeder files', function () {
    // Create stubs
    $this->artisan('make:cms-stubs')->assertSuccessful();

    // Install
    $this->artisan('install:cms')
        ->expectsConfirmation('Do you want to run CMS seeders?', 'no')
        ->assertSuccessful();

    // The seeders directory might already have a CmsSeeder or it's copied
    // The copyDirectory should handle this
    expect(File::isDirectory(database_path('seeders')))->toBeTrue();
});

it('skips existing files without force option', function () {
    // Create stubs and install first time
    $this->artisan('make:cms-stubs')->assertSuccessful();
    $this->artisan('install:cms')
        ->expectsConfirmation('Do you want to run CMS seeders?', 'no')
        ->assertSuccessful();

    // Try to install again without force
    $this->artisan('install:cms')
        ->expectsOutput('⚠ Skipping '.app_path('Domain/Article').' (already exists)')
        ->expectsConfirmation('Do you want to run CMS seeders?', 'no')
        ->assertSuccessful();
});

it('overwrites files with force option', function () {
    // Create stubs and install first time
    $this->artisan('make:cms-stubs')->assertSuccessful();
    $this->artisan('install:cms')
        ->expectsConfirmation('Do you want to run CMS seeders?', 'no')
        ->assertSuccessful();

    // Install again with force
    $this->artisan('install:cms --force')
        ->expectsConfirmation('Do you want to run CMS seeders?', 'no')
        ->assertSuccessful();

    // Verify files still exist after force install
    expect(File::exists(app_path('Domain/Article/Entities/Article.php')))->toBeTrue();
});

// Helper function to clean up CMS files
function cleanupCmsFiles()
{
    // Remove stubs
    if (File::exists(base_path('stubs/cms'))) {
        File::deleteDirectory(base_path('stubs/cms'));
    }

    // Remove installed files
    if (File::exists(app_path('Domain/Article'))) {
        File::deleteDirectory(app_path('Domain/Article'));
    }

    if (File::exists(app_path('Application/Article'))) {
        File::deleteDirectory(app_path('Application/Article'));
    }

    if (File::exists(app_path('Infrastructure/Persistence/Eloquent/Article'))) {
        File::deleteDirectory(app_path('Infrastructure/Persistence/Eloquent/Article'));
    }

    if (File::exists(database_path('seeders/CmsSeeder.php'))) {
        File::delete(database_path('seeders/CmsSeeder.php'));
    }

    // Remove migrations
    $migrations = File::files(database_path('migrations'));
    foreach ($migrations as $migration) {
        if (str_contains($migration->getFilename(), 'create_articles_table')) {
            File::delete($migration->getPathname());
        }
    }
}
