# Manual installation (no Docker)

## Required PHP extensions
`intl` (mandatory — Filament), `pdo_mysql`, `mbstring`, `bcmath`, `zip`,
`openssl`, `tokenizer`, `xml`, `ctype`, `json`, and `curl`.

## Services
PHP 8.4 (8.3 min) · MariaDB/MySQL · Node.js 20+ · Composer 2.

## Steps
```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
```

Do not run the vendor publish commands on this checkout unless you are
deliberately replacing existing migrations/config. The required app migrations
and Filament panel provider are already in the project.

## Running
```bash
php artisan serve            # http://127.0.0.1:8000
php artisan queue:work       # only needed if QUEUE_CONNECTION is not sync
php artisan schedule:work    # only needed when scheduled tasks are added
```

## Mail testing
Use Mailpit/Mailhog, or `MAIL_MAILER=log` to write to storage/logs/laravel.log.

## Common installation errors
- **Filament dependency conflict**: keep Laravel and Filament/Pest constraints on
  compatible major versions.
- **`ext-intl` missing**: Filament will not boot without it.
- **Duplicate table on migrate**: you published Spatie migrations *and* kept the
  stubs. Delete the stubs in database/migrations/.
- **419 on forms**: run `php artisan optimize:clear` and verify the browser is
  using the current login form, not a cached stale page.

## Shared hosting / icsajobs.com
Use `.env.production.example` as the checklist for the live `.env` values.
Set the real database credentials from the hosting control panel, generate a
new `APP_KEY`, keep `APP_DEBUG=false`, and run:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

If HTTPS is enabled for `icsajobs.com`, set `APP_URL=https://icsajobs.com` and
`SESSION_SECURE_COOKIE=true`.
