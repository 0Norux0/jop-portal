# Demo Credentials

These accounts are created by `php artisan migrate --seed` using the current local `.env` settings.

| Account | Email | Password | Role | Sign-in area |
| --- | --- | --- | --- | --- |
| Super Admin | `root@localhost.com` | `Root@icsa123` | Super administrator | `/admin` |
| Demo Admin | `admin@jobportal.test` | `Demo@icsa123` | Administrator | `/admin` |
| Demo Employer | `employer@jobportal.test` | `Demo@icsa123` | Employer | `/business` |
| Demo Candidate | `candidate@jobportal.test` | `Demo@icsa123` | Job seeker | `/dashboard` |
| Demo Recruitment Agency | `agency@jobportal.test` | `Demo@icsa123` | Recruitment agency | `/business` |

## Important

- These credentials are for the local/demo environment only.
- The shared demo password is defined by `DEMO_ACCOUNT_PASSWORD` in `.env`.
- The super-admin password is defined by `SUPER_ADMIN_PASSWORD` in `.env`.
- Change or remove these credentials before deploying anywhere public.
