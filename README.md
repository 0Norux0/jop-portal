# Global AI-Assisted Job Portal — Phase 1A Foundation

A modular-monolith Laravel foundation: authentication, multi-role users, an
administration panel, login-activity tracking, audit logging, queued
notifications, and a reproducible Docker development environment.

> **Brand name is configurable.** It is read from `config/jobportal.php`
> (`JOBPORTAL_BRAND_NAME`) and never hard-coded into source. "Job Portal" is a
> placeholder.

> **Status honesty.** This package was generated as source code. It has **not**
> been installed, migrated, or test-run in the environment that produced it.
> The 13 test files are **generated, not executed**. Run them yourself per
> `docs/VERIFICATION-CHECKLIST.md`.

---

## Stack

| Component | Version | Notes |
|---|---|---|
| PHP | 8.4 | Laravel 13 needs 8.3+; `ext-intl` required by Filament |
| Laravel | ^13.0 | Released 17 Mar 2026; 13.7.0+ |
| Filament | ^5.6 | **Not `^5.0`** — 5.0–5.3 do not support Laravel 13 |
| Livewire | ^4.1 | Required by Filament 5 |
| Tailwind CSS | ^4.1 | CSS-first config via `@tailwindcss/vite` |
| spatie/laravel-permission | ^6.0 | Roles + multiple roles per user |
| spatie/laravel-activitylog | ^4.0 | Audit logging |
| laravel/fortify | ^1.25 | Auth + TOTP two-factor primitives |
| PostgreSQL | 16 | |
| Redis | 7 | Sessions, cache, queue |

See `docs/VERSIONS.md` for the compatibility reasoning and the items that need
runtime confirmation.

## Domain layout

```
app/Domain/Identity/      User, enums, login activity, actions
app/Domain/Shared/        cross-cutting concerns (HasPublicId)
app/Actions/Fortify/      registration & password actions
app/Filament/             admin panel resources & widgets
app/Notifications/        queued notifications
app/Policies/             authorisation policies
```

Business logic lives in Action/Service classes — not controllers, not Blade,
not Filament resources.

## Quick start (Docker)

```bash
cp .env.example .env
# set SUPER_ADMIN_EMAIL (and optionally SUPER_ADMIN_PASSWORD) in .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app npm install && docker compose exec app npm run build
```

App: <http://localhost:8080> · Admin: <http://localhost:8080/admin> ·
Mailpit: <http://localhost:8025>

Full steps: `docs/INSTALL-DOCKER.md`. Without Docker: `docs/INSTALL-MANUAL.md`.

## Administrator two-factor authentication

2FA is built at the Fortify layer (TOTP with confirmation + recovery codes;
enabled in `config/fortify.php`). **The enforcement hook that requires admins to
have 2FA before entering the Filament panel is marked pending runtime
verification** — Filament 5 has its own panel auth flow whose exact 2FA
integration API must be confirmed on a live install. It is not presented as
finished. See `docs/SECURITY.md`.

## Tests

```bash
docker compose exec app php artisan test
```

13 Pest files under `tests/Feature`. Generated, not yet executed here.

## What is intentionally NOT in Phase 1A

No organisations, jobs, applications, candidate/employer profiles, CVs,
portfolios, video, payments, packages, embeddings, or AI request-log tables.
Those belong to later phases. AI is globally disabled and requires no provider.
