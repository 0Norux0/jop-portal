# Employer Business Area TODO

This file captures a future feature request. Do not implement this until the user explicitly says to start it.

## Goal

After an employer creates an account, the site should give them a proper business/employer area inspired by LinkedIn's business menu. It should feel like a real employer command center, not a static page.

Employers should be able to manage hiring, job posts, company identity, billing/admin details, promotion options, premium/growth tools, and employee learning options from one place.

## Core Entry Points

- [x] Add an employer dashboard link after employer registration.
- [x] Add an employer/business menu in the authenticated navigation.
- [x] Show employer-only options only to employer accounts.
- [x] Keep candidate/job-seeker accounts out of employer tools.
- [x] Keep public visitors out of private employer pages.
- [x] Add a clean empty state for employers who have not created a company profile yet.

## Business Menu Items

- [x] Hire talent / recruit candidates.
- [x] Post a job for free.
- [x] Create a Company Page.
- [x] Manage billing and account details / Admin Center.
- [x] Advertise or promote jobs/company pages.
- [x] Premium/growth options.
- [x] Learning/training options for employees.

## Employer Dashboard

- [x] Show active job posts.
- [x] Show draft job posts.
- [x] Show total applicants.
- [ ] Show new applicants since last login.
- [x] Show shortlisted candidates.
- [x] Show interview/request status.
- [ ] Show company profile completion status.
- [x] Show quick actions for posting a job, editing company page, reviewing applicants, and managing billing.

## Company Page

- [x] Allow employers to create a company page.
- [x] Allow employers to edit company name.
- [x] Allow employers to upload/change company logo.
- [x] Allow employers to upload/change cover image.
- [x] Allow employers to edit industry.
- [x] Allow employers to edit company size.
- [x] Allow employers to edit headquarters/location.
- [x] Allow employers to edit website URL.
- [x] Allow employers to edit about/company description.
- [x] Allow employers to add social links.
- [x] Allow employers to publish/unpublish the company page.
- [x] Add public company profile pages linked from jobs.

## Job Posting

- [x] Add employer job creation flow.
- [x] Support free job posting.
- [x] Support job drafts.
- [x] Support publishing/unpublishing jobs.
- [x] Support job title.
- [x] Support company selection.
- [x] Support location.
- [x] Support remote/on-site/hybrid.
- [x] Support salary range and currency.
- [x] Support job category.
- [x] Support employment type.
- [x] Support description.
- [x] Support responsibilities.
- [x] Support requirements.
- [x] Support benefits.
- [x] Support visa/work permit notes.
- [x] Support application deadline.
- [x] Support applicant questions.
- [x] Show confirmation after posting.
- [x] Link posted jobs to the public jobs listing.

## Recruiting / Candidate Search

- [x] Add a candidate discovery page for employers.
- [x] Filter candidates by job category.
- [x] Filter candidates by country/location.
- [x] Filter candidates by skills.
- [ ] Filter candidates by experience level.
- [ ] Filter candidates by visa/work permit status.
- [x] Allow employers to save candidates.
- [ ] Allow employers to shortlist candidates.
- [ ] Allow employers to contact or invite candidates to apply.
- [x] Add permissions so employers can only see allowed candidate data.

## Applicant Management

- [x] Add applicant list per job.
- [x] Add applicant detail page.
- [x] Show CV/resume link if uploaded.
- [ ] Show video profile if available.
- [ ] Show portfolio links if available.
- [x] Add applicant statuses: new, reviewed, shortlisted, rejected, interview, hired.
- [x] Allow employers to update applicant status.
- [x] Allow employers to add internal notes.
- [x] Allow employers to schedule or request an interview.
- [x] Add applicant search/filter.

## Billing / Admin Center

- [x] Add billing/account settings page.
- [x] Show company account owner.
- [x] Allow updating company account contact email.
- [x] Add placeholders for billing plan/status.
- [x] Add invoice history placeholder.
- [x] Add payment method placeholder.
- [x] Add team members placeholder.
- [x] Add role/permission placeholders for future employer staff accounts.

## Advertising / Promotion

- [x] Add promote job option.
- [x] Add featured job placeholder.
- [x] Add company page promotion placeholder.
- [x] Add campaign list placeholder.
- [x] Add campaign budget placeholder.
- [x] Add campaign performance placeholder.
- [x] Keep promotion features disabled or clearly marked until payment/billing is real.

## Premium / Growth

- [x] Add premium tools page.
- [x] Add placeholders for boosted reach.
- [x] Add placeholders for advanced candidate filters.
- [x] Add placeholders for company analytics.
- [x] Add placeholders for applicant insights.
- [x] Add upgrade flow placeholder.

## Learning / Training

- [x] Add employee learning page.
- [x] Add training/course placeholders.
- [x] Add saved training placeholders.
- [x] Add employee development placeholders.
- [x] Keep it modular so real course content can be added later.

## Admin Side Requirements

- [x] Admin can view employer accounts.
- [x] Admin can view company pages.
- [ ] Admin can approve/reject company pages if moderation is enabled.
- [x] Admin can view employer job posts.
- [ ] Admin can approve/reject job posts if moderation is enabled.
- [x] Admin can manage featured/promoted jobs.
- [ ] Admin can manage business menu labels/content where appropriate.
- [x] Admin can manage billing/premium placeholders or settings.

## Dynamic / Modular Requirements

- [ ] No hard-coded employer menu text where it should be editable.
- [x] No hard-coded company page content.
- [x] No hard-coded job content.
- [x] Use database-backed models for employer business data.
- [x] Use admin resources/settings for configurable text and options.
- [x] Keep public pages, employer pages, and admin resources connected to the same real data.
- [x] Make feature sections easy to enable/disable later.

## Security / Access Rules

- [x] Employer pages require login.
- [x] Employer pages require employer account type.
- [x] Admin-only pages stay admin-only.
- [x] Employers cannot edit another employer's company.
- [x] Employers cannot view private applicant data for jobs they do not own.
- [x] Do not show demo/admin credentials anywhere in the UI.
- [x] Validate uploaded company logos and cover images.

## Suggested Build Order

1. Create employer dashboard route and layout.
2. Add employer-only navigation/business menu.
3. Add company page create/edit flow.
4. Add job post create/edit/publish flow.
5. Add applicant management pages.
6. Add candidate discovery/search tools.
7. Add billing/admin center placeholders.
8. Add advertising/premium/learning placeholders.
9. Add admin resources and moderation controls.
10. Test account permissions end to end.

## Test Accounts Needed When Implemented

- [x] Employer test account with a company page.
- [ ] Employer test account without a company page.
- [x] Candidate test account with profile/CV/video/portfolio data.
- [x] Admin account that can inspect all employer data.

## Acceptance Checklist

- [x] A new employer can register and reach the employer dashboard.
- [x] The employer can create a company page.
- [x] The employer can post a real job.
- [x] The posted job appears publicly.
- [x] The employer can view applicants for their own jobs.
- [x] Candidate accounts cannot access employer tools.
- [x] Public visitors cannot access employer private pages.
- [x] Admin can manage employer/company/job data.
- [x] The whole feature is data-driven and customizable.
