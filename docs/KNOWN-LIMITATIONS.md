# Known limitations (Phase 1A)

1. **Not executed.** Generated as source only; not installed, migrated, or
   tested in the originating environment. Treat all tests as unverified until
   run.
2. **Admin 2FA enforcement pending.** Fortify TOTP is enabled, but forcing 2FA
   at the Filament panel boundary needs a live-install hook and verification.
3. **Spatie / Fortify tags** require runtime confirmation of clean Laravel 13
   resolution (see VERSIONS.md).
4. **Framework config files** for cache/queue/session/mail/logging/filesystems
   are produced by the framework on install — only customised configs ship here
   (see config/README.md).
5. **Dev Docker is not production.** No TLS, no hardened images, dev secrets.
6. **Out of scope by design:** organisations, memberships, agency relationships,
   jobs, applications, profiles, CVs, portfolios, video, payments, packages,
   embeddings, AI request logs. Phase 1 mentioned organisation foundations;
   Phase 1A explicitly narrowed scope to identity/auth/roles/admin shell, so
   organisation tables are deferred to the next batch.
