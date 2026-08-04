# Local installation with Docker

## Prerequisites
- Docker Engine 24+ and the Docker Compose plugin.
- Ports free: 8080 (app), 3306 (MariaDB), 8025 + 1025 (mailpit).

## Steps

1. **Environment**
   ```bash
   cp .env.example .env
   ```
   Edit `.env`:
   - Set `SUPER_ADMIN_EMAIL`. Optionally set `SUPER_ADMIN_PASSWORD`; if left
     blank, the seeder generates a one-time password and prints it once.
   - Confirm `DB_HOST=mariadb` and the MariaDB credentials.

2. **Build & start**
   ```bash
   docker compose up -d --build
   docker compose ps        # wait for MariaDB health checks
   ```

3. **Install PHP dependencies**
   ```bash
   docker compose exec app composer install
   ```

4. **App key**
   ```bash
   docker compose exec app php artisan key:generate
   ```

5. **Publish package assets/migrations (first install only)**
   ```bash
   docker compose exec app php artisan vendor:publish \
     --provider="Spatie\Permission\PermissionServiceProvider"
   docker compose exec app php artisan vendor:publish \
     --provider="Laravel\Fortify\FortifyServiceProvider"
   docker compose exec app php artisan filament:install --panels
   ```
   > If you publish the Spatie migrations, delete the matching stub files in
   > `database/migrations/` (commented at the top) to avoid duplicate tables.

6. **Migrate & seed**
   ```bash
   docker compose exec app php artisan migrate --seed
   ```
   Note the generated super-admin password if one was printed.

7. **Build front-end assets**
   ```bash
   docker compose exec app npm install
   docker compose exec app npm run build
   ```

8. **Verify**
   - App: http://localhost:8080
   - Admin: http://localhost:8080/admin
   - Mail: http://localhost:8025

The `queue` and `scheduler` services run automatically:
```bash
docker compose logs -f queue scheduler
```

## Resetting
```bash
docker compose down
rm -rf docker/data
docker compose up -d --build
```
