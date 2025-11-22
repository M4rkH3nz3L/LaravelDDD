<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<strong>Laravel DDD Starter Kit</strong><br>
<em>Opinionated, yet 100% Laravel-compatible DDD foundation</em>
</p>

<p align="center">
<a href="#"><img src="https://img.shields.io/badge/status-active-success" alt="Project Status"></a>
<a href="#"><img src="https://img.shields.io/badge/architecture-DDD-blue" alt="DDD Architecture"></a>
<a href="https://php.net"><img src="https://img.shields.io/badge/PHP-%5E8.2-777BB4" alt="PHP Version"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-lightgrey" alt="License"></a>
</p>

---

## About Laravel DDD Starter Kit

The **Laravel DDD Starter Kit** is a starter project/template that introduces **Domain-Driven Design (DDD)** architecture while preserving the native Laravel experience.
It's designed as if Laravel's creators had extended the framework themselves – no core modifications, no hacks, just clean, framework-compatible structure.

The Starter Kit's goals:

- **Clean separation** between Domain, Application, Infrastructure, and UI layers.
- **Laravel-flavored developer experience** – everything can be generated with Artisan commands.
- **Code quality preservation** – following PSR-12 / Laravel style, SOLID and DDD principles.
- **Scalability** – suitable foundation for large, modular, enterprise applications.

### Key Features

- Structured DDD folder layout:
  - `app/Domain` – entities, value objects, domain services, repository interfaces
  - `app/Application` – use cases (command + handler + DTO)
  - `app/Infrastructure` – Eloquent models, repository implementations, service providers
- **Artisan commands** for DDD environment:
  - `php artisan ddd:context User`
  - `php artisan ddd:entity User User`
  - `php artisan ddd:vo User Email`
  - `php artisan ddd:use-case User RegisterUser`
  - `php artisan ddd:eloquent-model User User`
  - `php artisan ddd:controller User RegisterUser --type=api`
- Built on Laravel features:
  - Eloquent ORM, migrations, service container, service providers
  - Doesn't conflict with native `app/Models`, `routes/`, `config/` structure
- The Domain layer is **framework-independent**, Infrastructure bridges it to the Laravel world.

---

## Why DDD With Laravel?

Laravel already provides an excellent developer experience. The DDD Starter Kit extends this with:

- **Clean separation of business logic** from the framework – easier to test, maintainable long-term code.
- Introduction of **bounded contexts** (e.g., `User`, `Order`, `Billing`), with well-organized modules.
- **Development from the terminal** – consistently create with Artisan commands:
  - domain entities,
  - value objects,
  - repository interfaces and implementations,
  - use cases,
  - thin controllers.

The Starter Kit's goal is to ensure DDD architecture **accelerates** rather than slows down development.

---

## Directory Structure Overview

The most important folders:

```text
app/
├── Domain/
│   ├── Shared/
│   │   ├── Contracts/
│   │   ├── Exceptions/
│   │   └── ValueObjects/
│   └── {ContextName}/
│       ├── Entities/
│       ├── ValueObjects/
│       ├── Repositories/
│       └── Services/
│
├── Application/
│   └── {ContextName}/
│       ├── Commands/
│       ├── Queries/
│       ├── Handlers/
│       └── DTO/
│
├── Infrastructure/
│   ├── Persistence/
│   │   └── Eloquent/
│   │       └── {ContextName}/
│   │           ├── {Entity}Model.php
│   │           └── {Entity}RepositoryEloquent.php
│   └── Providers/
│       └── DddServiceProvider.php
│
├── Http/
│   └── Controllers/
└── Providers/
```

---

## Installation

```bash
composer create-project your-vendor/laravel-ddd my-app
cd my-app
composer install
php artisan migrate
```

---

## Usage

### Creating a New Bounded Context

```bash
php artisan ddd:context User
```

This creates the following structure:
- `app/Domain/User/`
- `app/Application/User/`
- `app/Infrastructure/Persistence/Eloquent/User/`

### Creating Domain Entities

```bash
php artisan ddd:entity User User
```

Creates `app/Domain/User/Entities/User.php`

### Creating Value Objects

```bash
php artisan ddd:vo User Email
```

Creates `app/Domain/User/ValueObjects/Email.php`

### Creating Use Cases

```bash
php artisan ddd:use-case User RegisterUser
```

Creates:
- `app/Application/User/Commands/RegisterUserCommand.php`
- `app/Application/User/Handlers/RegisterUserHandler.php`
- `app/Application/User/DTO/RegisterUserDTO.php`

### Creating Eloquent Models

```bash
php artisan ddd:eloquent-model User User
```

Creates `app/Infrastructure/Persistence/Eloquent/User/UserModel.php`

### Creating Controllers

```bash
php artisan ddd:controller User RegisterUser --type=api
```

Creates `app/Http/Controllers/User/RegisterUserController.php`

---

## License

The Laravel DDD Starter Kit is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
