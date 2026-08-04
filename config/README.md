# Configuration notes

This bundle ships only the configuration files that carry **project-specific**
settings or that the application code references directly:

- `app.php`, `auth.php`, `database.php` — customised (MariaDB default, custom User model).
- `fortify.php` — auth + 2FA features.
- `jobportal.php` — project settings, feature flags, AI preparation.

The remaining standard Laravel config files (`cache.php`, `queue.php`,
`session.php`, `filesystems.php`, `mail.php`, `logging.php`, `services.php`,
`permission.php`, `activitylog.php`) are produced by the framework and the
installed packages. After `composer install`, generate/publish them with:

```
php artisan config:publish        # optional, to customise framework configs
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
```

Set these in `.env` so the framework defaults match this project:

```
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local
```

> If you publish the Spatie permission/activitylog migrations, delete the
> equivalent stub migrations in `database/migrations/` (clearly commented at the
> top of each stub) to avoid duplicate-table errors.
