# Site Accounts

These are the local/demo accounts currently seeded for this job portal.

## Super Admin

- Email: `root@localhost.com`
- Password: stored in local `.env` as `SUPER_ADMIN_PASSWORD`
- Role: Super administrator
- Area: `/admin`

## Demo Admin

- Email: `admin@jobportal.test`
- Password: local `.env` value `DEMO_ACCOUNT_PASSWORD`
- Role: Administrator
- Area: `/admin`

## Demo Employer

- Email: `employer@jobportal.test`
- Password: local `.env` value `DEMO_ACCOUNT_PASSWORD`
- Role: Employer
- Area: `/business`
- Company: `Northbridge Care Group`

## Demo Candidate

- Email: `candidate@jobportal.test`
- Password: local `.env` value `DEMO_ACCOUNT_PASSWORD`
- Role: Job seeker
- Area: `/dashboard`

## Demo Recruitment Agency

- Email: `agency@jobportal.test`
- Password: local `.env` value `DEMO_ACCOUNT_PASSWORD`
- Role: Recruitment agency
- Area: `/business`

## Notes

- Demo accounts come from `database/seeders/DemoAccountsSeeder.php`.
- Demo account passwords come from local `.env` value `DEMO_ACCOUNT_PASSWORD`.
- The super admin comes from `.env` values used by `database/seeders/SuperAdminSeeder.php`.
- The repo documentation intentionally avoids committing plaintext passwords.
- Do not show these credentials inside public website screens.
