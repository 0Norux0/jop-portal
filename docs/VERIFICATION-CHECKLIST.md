# Runtime verification checklist

These are steps for the developer/deployment environment. **None are marked
complete** — the generating environment had no network, PHP, database, or Redis,
so nothing here was executed.

## Install
- [ ] Containers start successfully (`docker compose ps` all healthy)
- [ ] `composer install` resolves with Filament `^5.6` (no dependency conflict)
- [ ] Application key generated
- [ ] Database connection successful
- [ ] Redis connection successful
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
- [ ] APP_DEBUG=false, SESSION_SECURE_COOKIE=true
- [ ] HTTPS + security headers at proxy
- [ ] Admin 2FA enforcement hook wired and verified
