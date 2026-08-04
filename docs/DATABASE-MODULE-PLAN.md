# Database Module Plan

This plan maps the portal checklist into database modules. Some modules already have tables in Phase 1A, while others are planned capability boundaries for later migrations.

## Core Identity

- Users
- Roles
- Recruiters
- Admin logs
- Notifications
- Messages

## Reference Data

- Countries
- Cities
- Currencies
- Languages
- Skills
- Industries
- Job categories

## Candidate Modules

- Candidates
- Candidate profiles
- Candidate CVs
- Candidate videos
- Candidate portfolios
- Candidate certificates
- Candidate work experience
- Candidate education
- Candidate preferred countries
- Candidate verification

## Employer And Hiring

- Employers
- Companies
- Jobs
- Job locations
- Job applications
- Saved jobs
- Job alerts
- Employer verification

## Monetization

- Packages
- Payments
- Invoices
- Credits

## Trust And Content

- Reports
- Complaints
- Blog posts
- SEO pages
- Testimonials
- Success stories

## Optional Institute Tables

- Institutes
- Branches
- Courses
- Student records
- Graduate verification
- Certificate numbers
- Placement records

## Implementation Rule

Each module should get a migration, model, policy, admin resource, seed data, and public/API surface only when its capability moves from planned to active.
