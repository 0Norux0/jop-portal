# Job Portal Build Log and Remaining Work

Last updated: 2026-08-08

## Completed Today

- Configured the project for local XAMPP/MariaDB use and disabled Redis-dependent runtime behavior.
- Fixed Laravel dependency issues enough for the app to install/build locally.
- Added a working `.env` setup for MariaDB and local demo credentials.
- Fixed missing layout and Vite manifest issues by restoring app layout/build behavior.
- Built the public homepage from the supplied design and made it the template direction for public pages.
- Added dynamic site customization for brand, logo, favicon, colors, homepage copy, navigation, footer, and contact links.
- Added public page text management for editable page titles, eyebrows, and descriptions.
- Added portal content management for public jobs/candidates/employers-style data.
- Added real demo accounts and demo employers/jobs so accounts can be used for testing.
- Added employer workspace pages for overview, company page, jobs, applicants, candidate search, admin center, and advertising requests.
- Added public company profile pages and linked jobs back to employer profiles.
- Added a centralized country repository so country lists can come from one code location.
- Added country-based default timezone selection during account creation.
- Added maintenance mode settings in the admin panel.
- Added `docs/SITE_ACCOUNTS.md` without committing plaintext passwords.
- Improved public login/signup screens to match the homepage style.
- Added password show/hide controls to login, signup, password reset, and confirm-password forms.
- Reworked signup nationality into a country-backed list.
- Improved signup work preferences from an awkward multi-select to checkbox-style controls.
- Fixed candidate dashboard overview cards so metric cards no longer stretch tall.
- Made candidate profile-completion action buttons clickable.
- Made recommended jobs use latest published job data.
- Made saved/applied job cards link to job pages as an interim step.
- Removed the fake candidate application pipeline section.
- Removed fake employer messages from the candidate dashboard.
- Fixed safe logout flow to avoid page-expired behavior.
- Restyled the admin login page and removed public credential hints.
- Added an employer portal customization page at `/admin/employer-portal-customization`.
- Made employer sidebar labels and Admin Center text editable from the admin panel.
- Fixed company cover image readability by moving company text onto a dark overlay.
- Cleared Laravel caches, rebuilt frontend assets, committed, and pushed the latest work.

## Being Completed In This Pass

These items were implemented in source code after the previous push:

- Added `saved_jobs` migration and model for database-backed saved jobs.
- Added `conversation_messages` migration and model for basic employer/candidate messaging.
- Added `CandidateWorkspaceController` for candidate dashboard data, saved jobs, applications, and messages.
- Added candidate pages:
  - `/saved-jobs`
  - `/applications`
  - `/messages`
- Replaced job-detail fake apply links with real save/apply forms for signed-in candidates.
- Added employer applicant messaging from the employer applicant review screen.
- Made the candidate dashboard use real saved job, application, and message collections.
- Removed inactive employer premium/learning controller actions.
- Removed inactive employer premium/learning Blade pages.
- Changed signup current status/job title from a free text datalist into a structured select.

Migration note: `php artisan migrate --force` was attempted, but MariaDB/XAMPP was not running. The app must run the migration again after MySQL is started.

## Still Left After This Pass

- Full payment processing, billing invoices, and payment method management.
- Full advertisement serving, payment, scheduling, approval workflow, and public ad display.
- Full employer team management with invited staff, roles, permissions, and account-owner transfer.
- Advanced recommendation logic beyond latest/oldest/published jobs.
- Advanced real-time messaging with notifications, unread counters, attachments, and moderation.
- Full candidate profile editor for CVs, portfolios, certifications, video, salary expectations, and verification.
- Full saved-job alerts with email/notification scheduling.
- Full application status timeline with interview scheduling and employer actions.
- Full timezone conversion across every date/time display in the portal.
- Full admin analytics dashboards and reporting views.
- A final end-to-end browser QA pass across public, candidate, employer, and admin pages.

## Notes

- Local plaintext passwords must stay in `.env` only.
- `public/company-assets/` contains local uploaded files and is intentionally not staged unless explicitly requested.
- The GitHub repo is currently pushed through commit `5a9d1c5` before this pass.
