# Employer Paid Services Checklist

This checklist tracks the employer monetization features requested for the job portal.

## Completed

- [x] Employers have plan-based job posting limits.
- [x] Employers can request featured job promotion.
- [x] Employers can search candidates from the employer workspace.
- [x] Employers have CV access credits.
- [x] Employers have candidate contact credits.
- [x] Employer subscription UI is disabled without deleting the underlying code.
- [x] Employer signup plan selection is disabled.
- [x] Employer plan-change requests are disabled.
- [x] Employers can request recruitment packages.
- [x] Employers can request premium candidate matching.
- [x] Employers can request AI recruitment tools.
- [x] Employer paid-service requests are stored in the database.
- [x] Employer credit usage is stored in the database.
- [x] Admin can view and manage paid-service requests.
- [x] Employer dashboard shows plan and credit status.
- [x] Employers can request upgrades but cannot grant themselves paid plans or credits.
- [x] Only protected admins can change employer plan level and credit balances.
- [x] Public pages avoid internal roadmap notes.

## Implementation Notes

- No automatic payment gateway has been connected yet.
- Paid services are implemented as plan entitlements, credits, and admin-review requests.
- Free employers can test the workspace without paying, but paid-only actions create requests or use limited included credits.
- Credit balances are stored on the employer record.
- Credit usage is recorded in employer credit transactions.
- Service requests are recorded in employer service requests for admin follow-up.

## Future Enhancements

- Connect Stripe, bank transfer, or invoice-based payment approval.
- Add invoice PDFs and payment status history.
- Add team member roles for larger employer accounts.
- Add automated matching recommendations once enough candidate/job data exists.
