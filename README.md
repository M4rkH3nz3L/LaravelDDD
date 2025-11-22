<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<strong>Laravel DDD Starter Kit</strong><br>
<em>Opinionated, mégis 100%-ban Laravel-kompatibilis DDD alap</em>
</p>

<p align="center">
<a href="#"><img src="https://img.shields.io/badge/status-active-success" alt="Project Status"></a>
<a href="#"><img src="https://img.shields.io/badge/architecture-DDD-blue" alt="DDD Architecture"></a>
<a href="https://php.net"><img src="https://img.shields.io/badge/PHP-%5E8.2-777BB4" alt="PHP Version"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-lightgrey" alt="License"></a>
</p>

---

## About Laravel DDD Starter Kit

A **Laravel DDD Starter Kit** egy olyan indító projekt / sablon, amely a natív Laravel élményt megtartva vezet be **Domain-Driven Design (DDD)** architektúrát.  
Úgy lett tervezve, mintha a Laravel készítői gondolták volna tovább a keretrendszert – nincs core módosítás, nincs hack, csak tiszta, keretrendszer-kompatibilis struktúra.

A Starter Kit célja:

- **Tiszta szétválasztás** Domain, Application, Infrastructure és UI rétegek között.
- **Laravel-ízű fejlesztői élmény** – mindent Artisan parancsokkal tudsz generálni.
- **Kódminőség megőrzése** – PSR-12 / Laravel stílus, SOLID és DDD elvek mentén.
- **Bővíthetőség** – nagy, moduláris, vállalati alkalmazásokhoz is megfelelő alap.

### Főbb jellemzők

- Struktúrált DDD mappastruktúra:
  - `app/Domain` – entitások, value objectek, domain szolgáltatások, repository interface-ek
  - `app/Application` – use case-ek (command + handler + DTO)
  - `app/Infrastructure` – Eloquent modellek, repository implementációk, service providerek
- **Artisan parancsok** DDD környezethez (pl. tervezett parancsok):
  - `php artisan ddd:context User`
  - `php artisan ddd:entity User User`
  - `php artisan ddd:vo User Email`
  - `php artisan ddd:use-case User RegisterUser`
  - `php artisan ddd:eloquent-model User User`
  - `php artisan ddd:controller User RegisterUser --type=api`
- Laravel sajátosságaira épül:
  - Eloquent ORM, migrációk, service container, service providerek
  - Nem ütközik a natív `app/Models`, `routes/`, `config/` struktúrával
- A Domain réteg **framework-független**, az Infrastructure hidalja át a Laravel világába.

---

## Why DDD With Laravel?

Laravel már önmagában is kiváló fejlesztői élményt ad. A DDD Starter Kit ezt egészíti ki a következőkkel:

- **Üzleti logika tiszta elválasztása** a frameworktől – könnyebben tesztelhető, hosszú távon karbantartható kód.
- **Bounded context-ek** bevezetése (pl. `User`, `Order`, `Billing`), jól szervezett modulokkal.
- **Fejlesztés terminálból** – az Artisan parancsokkal konzisztensen hozhatsz létre:
  - domain entitásokat,
  - value objecteket,
  - repository interface-eket és implementációkat,
  - use case-eket,
  - vékony controllereket.

A Starter Kit célja, hogy a DDD architektúra **ne lassítsa**, hanem **felgyorsítsa** a fejlesztést.

---

## Directory Structure Overview

A legfontosabb mappák:

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
