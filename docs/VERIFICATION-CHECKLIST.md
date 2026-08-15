# Runtime verification checklist

Use this before uploading or switching `icsajobs.com` into production mode.
Record the actual command output for anything checked on the server.

## Install
- [ ] Containers start successfully (`docker compose ps` all healthy)
- [ ] `composer install` resolves with Filament `^5.6` (no dependency conflict)
- [ ] Application key generated
- [ ] Database connection successful
- [ ] Cache/session/queue settings match the intended deployment (`file` and
  `sync` are valid for the current no-Redis setup)
- [ ] Package assets/migrations published; stub migrations removed if duplicated
- [ ] Migrations successful
- [ ] Seeders successful (roles, permissions, settings, super-admin)
- [ ] Assets compile (`npm run build`)

## Functional
- [ ] Registration works; initial role assigned from purpose
- [ ] Verification email is queued (visible in Mailpit)
- [ ] Email verification link verifies the account
- [ ] Login works for an active, verified user
- [ ] Invalid login is rejected and recorded as `invalid_credentials`
- [ ] Repeated failures are throttled
- [ ] Password reset flow works end to end
- [ ] Password change updates the hash; other sessions handled
- [ ] Suspended user is blocked and recorded as `account_blocked`
- [ ] A user can hold multiple roles simultaneously
- [ ] Admin panel opens for an administrator
- [ ] Job seeker is denied the admin panel
- [ ] Suspended administrator is denied the admin panel
- [ ] Ordinary admin cannot grant super-administrator (throws)
- [ ] Super administrator can grant super-administrator
- [ ] Status change writes an activity-log row
- [ ] Activity-log properties contain no password / token / 2FA secret

## Tests
- [ ] `php artisan test` runs
- [ ] All 13 Pest files pass (report actual output)

## Security (production)
- [ ] APP_ENV=production and APP_DEBUG=false
- [ ] APP_URL matches the live domain (`http://icsajobs.com` now, or
  `https://icsajobs.com` after SSL is enabled)
- [ ] SESSION_SECURE_COOKIE=true when HTTPS is enabled
- [ ] `FILESYSTEM_LOCAL_SERVE=false` so private storage is not exposed
- [ ] HTTPS + security headers at proxy
- [ ] Admin 2FA enforcement hook wired and verified
