# Modular Capability Architecture

This project should work like the referenced `og pinoy school` pattern: capability sections are explicit, gateable, and easy to expand without rewriting the whole app.

## Current Foundation

- Capability registry: `config/capabilities.php`
- Capability helper: `app/Support/PortalCapabilities.php`
- Public route middleware: `portal.capability:{module}`
- Admin section middleware: `admin.section:{section}`
- Existing ecosystem content remains in `config/portal.php`

## Rule

New product areas must be introduced as capabilities first, then routes/views/controllers/models are attached to that capability.

Examples:

- `jobs`
- `candidates`
- `employers`
- `trust_safety`
- `international`
- `content`
- `courses`
- `internships`
- `credentials`
- `freelance_marketplace`
- `events`
- `ai_career`

## Why

This keeps the portal ready to grow from a job portal into a career ecosystem:

- Jobs
- Courses
- Internships
- Apprenticeships
- Scholarships
- Career coaching
- Employer CRM
- Recruitment agencies
- Skill assessments
- AI career advisor
- University placement portal
- Digital certificates
- Credential verification
- Freelance marketplace
- Mentorship
- Events and career fairs
- Talent marketplace

## Implementation Rule For Future Work

Before adding a new major feature:

1. Add or update the capability in `config/capabilities.php`.
2. Add route middleware `portal.capability:{key}` for public pages.
3. Add `admin.section:{section}` for protected admin routes.
4. Keep content/config centralized where possible.
5. Update `GLOBAL_JOB_PORTAL_CHECKLIST.md` only after the capability is visible or implemented.
