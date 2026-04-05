# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

### Development
```bash
composer run dev        # Start Laravel server, queue worker, and Vite concurrently
npm run dev             # Vite dev server only
```

### Build
```bash
npm run build           # Production frontend build
npm run build:ssr       # SSR production build
```

### Testing
```bash
./vendor/bin/pest                          # Run all tests
./vendor/bin/pest tests/Feature/           # Run feature tests only
./vendor/bin/pest tests/Unit/              # Run unit tests only
./vendor/bin/pest --filter=DashboardTest   # Run a single test file
```

### Code Quality
```bash
./vendor/bin/pint                          # Fix PHP code style (Laravel standard)
npm run lint                               # ESLint with auto-fix
npm run format                             # Prettier formatting for resources/
npm run format:check                       # Check formatting without fixing
```

### Database
```bash
php artisan migrate                        # Run migrations
php artisan migrate:fresh --seed           # Reset and re-seed database
php artisan db:seed                        # Run seeders
```

## Architecture

This is a **Laravel 12 + Vue 3** application using **Inertia.js** as the bridge — there is no separate REST API. The backend renders Inertia responses and Vue hydrates them into an SPA-like experience.

### Request Flow
1. Laravel routes (`routes/web.php`, `routes/auth.php`, `routes/settings.php`) handle requests
2. Controllers return `Inertia::render('PageName', $props)` responses
3. Inertia delivers the page component name + props as JSON
4. Vue renders the matching page component from `resources/js/pages/`

### Frontend Structure (`resources/js/`)
- `pages/` — Inertia page components (one per route)
- `layouts/` — Layout wrappers: `AppLayout` (authenticated), `AuthLayout` (guest)
- `components/ui/` — Radix Vue headless UI primitives styled with Tailwind
- `components/` — App-specific components (sidebar, header, nav, forms)
- `composables/` — Vue 3 composables (`useAppearance`, `useInitials`)
- `lib/utils.ts` — Utility functions (clsx/tailwind-merge helper)
- `types/` — TypeScript type definitions

### Backend Structure (`app/`)
- `Http/Controllers/Auth/` — Full auth flows (login, register, password reset, email verification)
- `Http/Controllers/Settings/` — Profile and password settings
- `Http/Requests/` — Form request validation classes
- `Models/User.php` — The only model; extends Authenticatable

### Routes
| File | Middleware | Purpose |
|------|-----------|---------|
| `routes/web.php` | public / `auth+verified` | Welcome page, dashboard |
| `routes/auth.php` | `guest` / `auth` | Full auth flows |
| `routes/settings.php` | `auth` | Profile, password, appearance |

### JavaScript Routes
Ziggy is configured — use `route('name')` in Vue/TypeScript just as in Blade.

### Testing
- **Pest PHP** (not plain PHPUnit) with `RefreshDatabase` trait
- Feature tests hit in-memory SQLite; no special setup needed
- Tests live in `tests/Feature/Auth/`, `tests/Feature/Settings/`, `tests/Feature/`

### Database
SQLite by default (`database/database.sqlite`). Schema: users, sessions, cache, jobs tables. Override with `DB_*` env vars for MySQL/PostgreSQL.