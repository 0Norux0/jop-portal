# Security checklist (Phase 1A)

Implemented in source:
- [x] CSRF protection on all web forms (Laravel default middleware).
- [x] Output escaping via Blade `{{ }}`.
- [x] Form Request / Validator validation on registration & password actions.
- [x] Authorisation policies (`UserPolicy`) + permission checks.
- [x] Super-admin `Gate::before` override; escalation guard prevents ordinary
      admins from granting super-admin (`AssignRoleToUser`).
- [x] Login throttling (configurable, email+IP keyed).
- [x] Password hashing (bcrypt; `BCRYPT_ROUNDS=4` only in the testing env).
- [x] Minimum 12-character passwords on register/reset/update.
- [x] Account-status gate at login: suspended/deactivated/scheduled-for-deletion
      users cannot authenticate.
- [x] Email verification required for protected routes (`verified` middleware).
- [x] Login-activity records store **metadata only** — no passwords, no raw
      request bodies; failure reasons are coarse categories.
- [x] Audit log (activitylog) logs name/email/status only; password,
      remember_token, and two_factor_secret are excluded via `$hidden` + `logOnly`.
- [x] Secrets in `.env`, never committed (`.gitignore`).
- [x] Soft deletes on users.
- [x] ULID public identifiers in routes; internal numeric IDs not exposed.

Requires runtime configuration / verification:
- [ ] `APP_DEBUG=false` and `SESSION_SECURE_COOKIE=true` in production.
- [ ] HTTPS + HSTS at the web/proxy layer (not configured in the dev nginx).
- [ ] **Admin 2FA enforcement** — Fortify TOTP is enabled, but the hook that
      *forces* admins to complete 2FA before the Filament panel loads must be
      wired and verified on a live install. Until then, 2FA is available but not
      mandatory at the panel boundary. Do not report admin 2FA as complete.
- [ ] Security headers (CSP, X-Frame-Options) — add via middleware/proxy.
- [ ] Rate limits tuned for production traffic.

## Sensitive data rules carried into later phases
- Never log CVs, identity documents, private messages, or API secrets.
- AI logging (future) stores operational metadata only; sensitive content is
  excluded by default (`AI_LOG_SENSITIVE_CONTENT=false`).
