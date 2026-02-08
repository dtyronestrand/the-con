# AGENTS.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Tech Stack

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Inertia.js v2 + Vue 3 + Tailwind CSS v4
- **Authentication**: Laravel Fortify with 2FA support
- **Testing**: Pest 4
- **Code Formatting**: Laravel Pint (PHP), Prettier + ESLint (TypeScript/Vue)
- **Route Generation**: Laravel Wayfinder (generates TypeScript functions for Laravel routes)
- **Mobile**: NativePHP Mobile (builds native iOS/Android apps)

## Development Commands

```bash
# Start development environment (server, queue, logs, vite concurrently)
composer run dev

# Run tests (use --filter to run specific tests)
php artisan test --compact
php artisan test --compact --filter=testName

# PHP code formatting (run before committing)
vendor/bin/pint --dirty

# Frontend linting and formatting
npm run lint
npm run format

# Build frontend assets
npm run build

# Create new files using artisan (always pass --no-interaction)
php artisan make:model ModelName --no-interaction
php artisan make:test --pest TestName --no-interaction
php artisan make:controller ControllerName --no-interaction
```

## Architecture

### Laravel 12 Structure

- `bootstrap/app.php` - Configure middleware, exceptions, and routing (no `app/Http/Kernel.php`)
- `bootstrap/providers.php` - Application service providers
- Console commands in `app/Console/Commands/` are auto-discovered

### Frontend Structure

- `resources/js/pages/` - Inertia Vue page components
- `resources/js/components/` - Reusable Vue components
- `resources/js/components/ui/` - Base UI components (shadcn/ui pattern)
- `resources/js/layouts/` - Layout components
- `resources/js/actions/` - Wayfinder-generated controller route functions
- `resources/js/routes/` - Wayfinder-generated named route functions

### Wayfinder Usage

Import type-safe route functions from `@/actions/` (controllers) or `@/routes/` (named routes):

```typescript
import StorePost from '@/actions/.../StorePostController';
import { show } from '@/routes/posts';

// Use with Inertia forms
form.submit(StorePost());
```

### Domain Models

- `User` - Authentication with 2FA (Fortify)
- `Category` - hasMany Services
- `Service` - belongsTo Category (name, url, icon, category_id)

## Code Conventions

- Use PHP 8 constructor property promotion
- Always use explicit return type declarations
- Create Form Request classes for validation (not inline)
- Use Eloquent relationships with return type hints
- Prefer `Model::query()` over `DB::` facade
- Use eager loading to prevent N+1 queries
- Vue components must have a single root element
- Check sibling files for existing patterns before creating new ones

## Testing Requirements

- Every change must have tests - write or update tests, then verify they pass
- Use Pest syntax: `php artisan make:test --pest TestName`
- Use model factories; check for existing states before manually setting up
- Most tests should be Feature tests

## NativePHP Mobile

This project can be built as a native iOS/Android app. On macOS you can build for both platforms; Windows/Linux can only build for Android.

Required `.env` variables for mobile builds:
```
NATIVEPHP_APP_ID=com.yourcompany.yourapp
NATIVEPHP_APP_VERSION="DEBUG"
NATIVEPHP_APP_VERSION_CODE="1"
```

Use JavaScript bridge in Vue components:
```typescript
import { camera, dialog, on, Events } from '#nativephp';
```
