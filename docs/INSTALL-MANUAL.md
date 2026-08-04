# Manual installation (no Docker)

## Required PHP extensions
`intl` (mandatory — Filament), `pdo_mysql`, `mbstring`, `bcmath`, `zip`,
`openssl`, `tokenizer`, `xml`, `ctype`, `json`, and `curl`.

## Services
PHP 8.4 (8.3 min) · MariaDB 11 · Node.js 20+ · Composer 2.

## Steps
```bash
cp .env.example .env          # set DB_HOST to 127.0.0.1 if MariaDB is local
composer install
php artisan key:generate
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
php artisan filament:install --panels
php artisan migrate --seed
npm install && npm run build
```

## Running
```bash
php artisan serve            # http://127.0.0.1:8000
php artisan queue:work       # separate terminal
php artisan schedule:work    # separate terminal
```

## Mail testing
Use Mailpit/Mailhog, or `MAIL_MAILER=log` to write to storage/logs/laravel.log.

## Common installation errors
- **Filament dependency conflict**: keep Laravel and Filament/Pest constraints on
  compatible major versions.
- **`ext-intl` missing**: Filament will not boot without it.
- **Duplicate table on migrate**: you published Spatie migrations *and* kept the
  stubs. Delete the stubs in database/migrations/.
- **419 on forms**: run `php artisan optimize:clear`.
