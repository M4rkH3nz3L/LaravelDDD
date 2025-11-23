<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

<p align="center">
    <strong>Laravel DDD Starter Kit</strong><br>
    <em>Enterprise-grade Domain-Driven Design foundation for Laravel</em>
</p>

<p align="center">
    <a href="https://packagist.org/packages/m4rkhenzel/laravel-ddd-starter"><img src="https://img.shields.io/packagist/v/m4rkhenzel/laravel-ddd-starter" alt="Latest Version"></a>
    <a href="https://packagist.org/packages/m4rkhenzel/laravel-ddd-starter"><img src="https://img.shields.io/packagist/dt/m4rkhenzel/laravel-ddd-starter" alt="Total Downloads"></a>
    <a href="#"><img src="https://img.shields.io/badge/architecture-DDD%20%2B%20CQRS-blue" alt="DDD + CQRS"></a>
    <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-%5E8.2-777BB4" alt="PHP Version"></a>
    <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-lightgrey" alt="License"></a>
</p>

---

## About Laravel DDD Starter Kit

The **Laravel DDD Starter Kit** provides a clean, scalable, enterprise-ready project structure built on **Laravel 12**, implementing **Domain-Driven Design (DDD)** and **CQRS** patterns — without modifying Laravel's core.

This starter kit is designed as if **Laravel natively supported DDD out-of-the-box**, while maintaining:

- ✅ Full compatibility with Laravel's conventions
- ✅ Eloquent ORM, Service Container, Providers, Routes, Middleware
- ✅ Zero framework hacks or overrides
- ✅ Complete Laravel ecosystem integration (Sanctum, Pest, Pint, etc.)

Perfect for teams and solo developers building **large, modular, maintainable** applications with clear separation of concerns.

---

## Why DDD + CQRS?

### Domain-Driven Design
- **Bounded Contexts**: Isolate business domains (User, Order, Payment, etc.)
- **Ubiquitous Language**: Code mirrors business terminology
- **Rich Domain Models**: Business logic lives in entities, not controllers
- **Strategic Design**: Clear boundaries between different parts of your application

### CQRS (Command Query Responsibility Segregation)
- **Commands**: Write operations (CreateUser, UpdateOrder)
- **Queries**: Read operations (GetUserById, ListOrders)
- **Handlers**: Dedicated classes for each use case
- **DTOs**: Type-safe data transfer between layers

---

## Key Features

### 🏗️ Clean Architecture Layers

```
┌─────────────────────────────────────┐
│         UI / HTTP Layer             │  Controllers, Routes, API
├─────────────────────────────────────┤
│       Application Layer             │  Commands, Queries, DTOs, Handlers
├─────────────────────────────────────┤
│         Domain Layer                │  Entities, Value Objects, Services
├─────────────────────────────────────┤
│     Infrastructure Layer            │  Eloquent, Repositories, External APIs
└─────────────────────────────────────┘
```

### ⚡ Built-In Artisan Generators

Create complete DDD modules from the terminal:

```bash
php artisan ddd:context Order        # Create bounded context
php artisan ddd:entity Order Order   # Create aggregate root
php artisan ddd:vo Order OrderId     # Create value object
php artisan ddd:use-case Order PlaceOrder  # Command + Handler + DTO
php artisan ddd:eloquent-model Order Order # Model + Repository
php artisan ddd:controller Order PlaceOrder --type=api  # Controller
```

### 📦 Modular Installation System

Add external modules as optional features using the stub system:

```bash
php artisan make:cms-stubs           # Generate CMS module stubs
php artisan install:cms              # Install CMS module into your app
```

This approach keeps your application lean by only including the functionality you need.

### 🧱 Framework-Independent Domain

- Pure PHP domain layer
- No Laravel dependencies in business logic
- Fully testable without framework
- Easy to migrate or extract

### 🧪 Testing Ready

- Pest 4 integration
- Browser testing support
- Separate unit and feature tests
- Factory pattern for test data

---

## Installation

### Create New Project

```bash
composer create-project m4rkhenzel/laravel-ddd-starter my-app
cd my-app
```

### Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

### Setup Database

```bash
# Configure your database in .env
php artisan migrate
```

### Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` — you're ready to build!

---

## Directory Structure

```
my-app/
├── app/
│   ├── Domain/                      # 🎯 Pure business logic
│   │   ├── Shared/
│   │   │   ├── Contracts/           # Repository interfaces
│   │   │   ├── Exceptions/          # Domain exceptions
│   │   │   └── ValueObjects/        # Shared VOs (Money, Email, etc.)
│   │   │
│   │   └── {Context}/               # Bounded Context (User, Order, etc.)
│   │       ├── Entities/            # Aggregate roots
│   │       ├── ValueObjects/        # Context-specific VOs
│   │       ├── Repositories/        # Repository interfaces
│   │       ├── Services/            # Domain services
│   │       └── Exceptions/          # Context exceptions
│   │
│   ├── Application/                 # 🔄 Use cases (CQRS)
│   │   └── {Context}/
│   │       ├── Commands/            # Write operations
│   │       ├── Queries/             # Read operations
│   │       ├── Handlers/            # Command/Query handlers
│   │       └── DTO/                 # Data Transfer Objects
│   │
│   ├── Infrastructure/              # 🔌 External integrations
│   │   ├── Persistence/
│   │   │   └── Eloquent/{Context}/
│   │   │       ├── {Entity}Model.php            # Eloquent models
│   │   │       └── {Entity}RepositoryEloquent.php  # Implementations
│   │   └── Providers/
│   │       └── DddServiceProvider.php
│   │
│   └── Http/
│       └── Controllers/             # 🌐 Thin HTTP controllers
│           └── {Context}/
│
├── routes/
│   ├── api.php                      # API routes
│   └── web.php                      # Web routes
│
└── tests/
    ├── Feature/                     # Integration tests
    ├── Unit/                        # Unit tests
    └── Browser/                     # Browser tests (Pest 4)
```

---

## Quick Start Guide

### 1. Create Your First Bounded Context

```bash
php artisan ddd:context Product
```

This creates the complete folder structure for the `Product` context:
- `app/Domain/Product/`
- `app/Application/Product/`
- `app/Infrastructure/Persistence/Eloquent/Product/`

### 2. Define Your Aggregate Root

```bash
php artisan ddd:entity Product Product
```

Edit `app/Domain/Product/Entities/Product.php`:

```php
<?php

namespace App\Domain\Product\Entities;

use App\Domain\Product\ValueObjects\ProductId;
use App\Domain\Product\ValueObjects\Price;

final class Product
{
    public function __construct(
        private ProductId $id,
        private string $name,
        private Price $price,
        private bool $isActive = true
    ) {
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function updatePrice(Price $newPrice): void
    {
        $this->price = $newPrice;
    }

    // Getters...
    public function id(): ProductId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): Price
    {
        return $this->price;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
```

### 3. Create Value Objects

```bash
php artisan ddd:vo Product Price
```

Edit `app/Domain/Product/ValueObjects/Price.php`:

```php
<?php

namespace App\Domain\Product\ValueObjects;

use InvalidArgumentException;

final readonly class Price
{
    public function __construct(
        private float $amount,
        private string $currency = 'USD'
    ) {
        if ($amount < 0) {
            throw new InvalidArgumentException('Price cannot be negative');
        }
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function equals(Price $other): bool
    {
        return $this->amount === $other->amount
            && $this->currency === $other->currency;
    }
}
```

### 4. Create a Use Case

```bash
php artisan ddd:use-case Product CreateProduct
```

This generates:
- `CreateProductCommand.php`
- `CreateProductHandler.php`
- `CreateProductDTO.php`

Edit the handler `app/Application/Product/Handlers/CreateProductHandler.php`:

```php
<?php

namespace App\Application\Product\Handlers;

use App\Application\Product\Commands\CreateProductCommand;
use App\Application\Product\DTO\CreateProductDTO;
use App\Domain\Product\Entities\Product;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Domain\Product\ValueObjects\ProductId;
use App\Domain\Product\ValueObjects\Price;

final readonly class CreateProductHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {
    }

    public function handle(CreateProductCommand $command): CreateProductDTO
    {
        $product = new Product(
            id: ProductId::generate(),
            name: $command->name,
            price: new Price($command->price, $command->currency)
        );

        $this->productRepository->save($product);

        return new CreateProductDTO(
            id: $product->id()->value(),
            name: $product->name(),
            price: $product->price()->amount(),
            currency: $product->price()->currency()
        );
    }
}
```

### 5. Create Eloquent Model & Repository

```bash
php artisan ddd:eloquent-model Product Product
```

This creates:
- `ProductModel.php` (Eloquent model)
- `ProductRepositoryEloquent.php` (Repository implementation)

### 6. Create API Controller

```bash
php artisan ddd:controller Product CreateProduct --type=api
```

Edit `app/Http/Controllers/Product/CreateProductController.php`:

```php
<?php

namespace App\Http\Controllers\Product;

use App\Application\Product\Commands\CreateProductCommand;
use App\Application\Product\Handlers\CreateProductHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CreateProductController extends Controller
{
    public function __construct(
        private readonly CreateProductHandler $handler
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
        ]);

        $command = new CreateProductCommand(
            name: $validated['name'],
            price: $validated['price'],
            currency: $validated['currency']
        );

        $dto = $this->handler->handle($command);

        return response()->json($dto, 201);
    }
}
```

### 7. Register Routes

Add to `routes/api.php`:

```php
use App\Http\Controllers\Product\CreateProductController;

Route::post('/products', CreateProductController::class);
```

### 8. Test Your API

```bash
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -d '{
    "name": "MacBook Pro",
    "price": 2499.99,
    "currency": "USD"
  }'
```

---

## Testing

### Run All Tests

```bash
php artisan test
```

### Run Specific Test File

```bash
php artisan test tests/Feature/Product/CreateProductTest.php
```

### Filter Tests

```bash
php artisan test --filter=CreateProduct
```

### Example Feature Test

Create `tests/Feature/Product/CreateProductTest.php`:

```php
<?php

use App\Infrastructure\Persistence\Eloquent\Product\ProductModel;

it('creates a product successfully', function () {
    $response = $this->postJson('/api/products', [
        'name' => 'Test Product',
        'price' => 99.99,
        'currency' => 'USD',
    ]);

    $response->assertCreated()
        ->assertJsonStructure([
            'id',
            'name',
            'price',
            'currency',
        ]);

    expect(ProductModel::count())->toBe(1);
});

it('validates required fields', function () {
    $response = $this->postJson('/api/products', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'price', 'currency']);
});
```

---

## Available Artisan Commands

### DDD Generators

| Command | Description |
|---------|-------------|
| `ddd:context {name}` | Create a new bounded context |
| `ddd:entity {context} {name}` | Create an aggregate root entity |
| `ddd:vo {context} {name}` | Create a value object |
| `ddd:use-case {context} {name}` | Create command + handler + DTO |
| `ddd:eloquent-model {context} {name}` | Create Eloquent model + repository |
| `ddd:controller {context} {name}` | Create a thin controller |
| `ddd:repository {context} {name}` | Create repository interface |
| `ddd:service {context} {name}` | Create domain service |

### Module Management

| Command | Description |
|---------|-------------|
| `make:cms-stubs` | Generate CMS module stub files |
| `install:cms [--force]` | Install CMS module from stubs |

---

## Modular Installation System

The Laravel DDD Starter Kit includes a powerful modular installation system that allows you to add optional features without bloating your core application.

### How It Works

1. **Generate Stubs**: Create pre-configured module files
2. **Install Modules**: Copy files to your application structure
3. **Keep Lean**: Only install what you need

### Example: CMS Module

#### Step 1: Generate CMS Stubs

```bash
php artisan make:cms-stubs
```

This creates a complete Article management system in `stubs/cms/`:

```
stubs/cms/
├── Domain/Article/
│   ├── Entities/Article.php
│   ├── ValueObjects/
│   │   ├── ArticleId.php
│   │   └── Slug.php
│   └── Repositories/ArticleRepositoryInterface.php
├── Application/Article/
│   ├── Commands/
│   ├── Queries/
│   ├── Handlers/
│   └── DTO/
├── Infrastructure/Persistence/Eloquent/Article/
│   ├── ArticleModel.php
│   └── ArticleRepositoryEloquent.php
└── database/
    ├── migrations/
    └── seeders/
```

#### Step 2: Install the CMS Module

```bash
php artisan install:cms
```

This command:
- Copies all files to the correct DDD layers
- Runs database migrations
- Optionally seeds sample data
- Clears application cache

The Article domain is now part of your application!

#### Step 3: Register Repository Binding

Add to `app/Infrastructure/Providers/DddServiceProvider.php`:

```php
$this->app->bind(
    \App\Domain\Article\Repositories\ArticleRepositoryInterface::class,
    \App\Infrastructure\Persistence\Eloquent\Article\ArticleRepositoryEloquent::class
);
```

### Creating Your Own Modules

You can create similar installation commands for any feature:

1. Create a `MakeYourModuleStubsCommand`
2. Generate stub files following DDD structure
3. Create an `InstallYourModuleCommand` to copy files
4. Keep modules optional and installable on-demand

**Benefits:**
- Clean separation of concerns
- Only install features you need
- Easy to share modules between projects
- Maintains DDD architecture

---

## Best Practices

### 1. Keep Domain Pure
- No Laravel dependencies in `Domain/` layer
- Use interfaces for external dependencies
- All business rules in entities and value objects

### 2. Use Value Objects
- Wrap primitives (Email, Money, OrderId)
- Validation in constructor
- Immutable objects

### 3. Single Responsibility
- One command/query per use case
- One handler per command/query
- Thin controllers (just validation + dispatch)

### 4. Repository Pattern
- Interfaces in `Domain/`
- Eloquent implementations in `Infrastructure/`
- Inject via Service Provider

### 5. Test Coverage
- Unit tests for domain logic
- Feature tests for use cases
- Browser tests for critical flows

---

## Architecture Decisions

### Why Separate Eloquent Models?

Domain entities are pure PHP classes focused on business logic. Eloquent models in `Infrastructure/` handle database persistence. This allows:

- Testing domain logic without database
- Switching ORMs if needed
- Clear separation of concerns

### Why CQRS?

Separating commands (writes) from queries (reads) provides:

- Clear intent in code
- Easier optimization (different read/write paths)
- Better scalability
- Simplified testing

### Why Bounded Contexts?

Large applications need clear boundaries:

- Prevents "big ball of mud"
- Enables team autonomy
- Allows independent evolution
- Reduces cognitive load

---

## Roadmap

- ✅ Packagist integration
- ✅ Laravel 12 support
- ✅ Complete artisan generators
- ✅ CQRS pattern implementation
- 🚧 Complete example contexts (User, Order)
- 🚧 Event sourcing support (optional)
- 🚧 CI/CD templates (GitHub Actions)
- 🚧 Documentation website
- 🚧 Video tutorials

---

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## License

The Laravel DDD Starter Kit is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).

---

## Resources

- **Packagist**: [m4rkhenzel/laravel-ddd-starter](https://packagist.org/packages/m4rkhenzel/laravel-ddd-starter)
- **GitHub**: [Your Repository](https://github.com/m4rkhenzel/laravel-ddd-starter)
- **Laravel Documentation**: [https://laravel.com/docs](https://laravel.com/docs)
- **DDD Resources**: [Domain-Driven Design Reference](https://www.domainlanguage.com/ddd/)

---

## Support

If you discover any security vulnerabilities, please email directly instead of using the issue tracker.

For bugs and feature requests, please use the [GitHub issue tracker](https://github.com/m4rkhenzel/laravel-ddd-starter/issues).

---

<p align="center">
    Made with ❤️ for the Laravel community
</p>
