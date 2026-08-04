# Version compatibility notes

## Confirmed
- **Laravel 13** released 17 March 2026 (13.7.0+). Requires **PHP 8.3 minimum**;
  support table covers PHP 8.3–8.5. This package targets **PHP 8.4**.
- **Filament 5** exists primarily to support **Livewire 4**. Critically,
  **Filament 5.0–5.3 did NOT support Laravel 13** (their `filament/support`
  required `illuminate/contracts ^11.28|^12.0`). **Filament 5.6+ added Laravel 13**
  (`^11.28|^12.0|^13.0`) and requires `livewire/livewire ^4.1` and `ext-intl`.
  → Constraint pinned to **`^5.6`**. Do not loosen to `^5.0`.

## Flagged for runtime confirmation
- `spatie/laravel-permission ^6.0` and `spatie/laravel-activitylog ^4.0`:
  widely expected to support Laravel 13, but confirm the exact tagged versions
  resolve cleanly during `composer install`. If a tag lags, bump the minor
  constraint to the first tag that declares `illuminate/* ^13.0`.
- `laravel/fortify ^1.25`: confirm the installed tag supports Laravel 13.
- Tailwind CSS 4 + Filament 5 asset pipeline: confirm `npm run build` emits the
  panel styles correctly.
- PHP 8.5: permitted by Laravel but some packages were still adding 8.5 support
  in early 2026 — stay on 8.4 unless you verify the full tree on 8.5.

## If a constraint fails to resolve
1. Run `composer why-not laravel/framework 13` to see the blocker.
2. Bump the offending package to its first Laravel-13 tag.
3. As a last resort for a single lagging package, use a `dev-main` alias in a
   fork — never disable platform checks globally.
