# Employer Business Area TODO

This file captures a future feature request. Do not implement this until the user explicitly says to start it.

## Goal

After an employer creates an account, the site should give them a proper business/employer area inspired by LinkedIn's business menu. It should feel like a real employer command center, not a static page.

Employers should be able to manage hiring, job posts, company identity, billing/admin details, promotion options, premium/growth tools, and employee learning options from one place.

## Core Entry Points

- [ ] Add an employer dashboard link after employer registration.
- [ ] Add an employer/business menu in the authenticated navigation.
- [ ] Show employer-only options only to employer accounts.
- [ ] Keep candidate/job-seeker accounts out of employer tools.
- [ ] Keep public visitors out of private employer pages.
- [ ] Add a clean empty state for employers who have not created a company profile yet.

## Business Menu Items

- [ ] Hire talent / recruit candidates.
- [ ] Post a job for free.
- [ ] Create a Company Page.
- [ ] Manage billing and account details / Admin Center.
- [ ] Advertise or promote jobs/company pages.
- [ ] Premium/growth options.
- [ ] Learning/training options for employees.

## Employer Dashboard

- [ ] Show active job posts.
- [ ] Show draft job posts.
- [ ] Show total applicants.
- [ ] Show new applicants since last login.
- [ ] Show shortlisted candidates.
- [ ] Show interview/request status.
- [ ] Show company profile completion status.
- [ ] Show quick actions for posting a job, editing company page, reviewing applicants, and managing billing.

## Company Page

- [ ] Allow employers to create a company page.
- [ ] Allow employers to edit company name.
- [ ] Allow employers to upload/change company logo.
- [ ] Allow employers to upload/change cover image.
- [ ] Allow employers to edit industry.
- [ ] Allow employers to edit company size.
- [ ] Allow employers to edit headquarters/location.
- [ ] Allow employers to edit website URL.
- [ ] Allow employers to edit about/company description.
- [ ] Allow employers to add social links.
- [ ] Allow employers to publish/unpublish the company page.
- [ ] Add public company profile pages linked from jobs.

## Job Posting

- [ ] Add employer job creation flow.
- [ ] Support free job posting.
- [ ] Support job drafts.
- [ ] Support publishing/unpublishing jobs.
- [ ] Support job title.
- [ ] Support company selection.
- [ ] Support location.
- [ ] Support remote/on-site/hybrid.
- [ ] Support salary range and currency.
- [ ] Support job category.
- [ ] Support employment type.
- [ ] Support description.
- [ ] Support responsibilities.
- [ ] Support requirements.
- [ ] Support benefits.
- [ ] Support visa/work permit notes.
- [ ] Support application deadline.
- [ ] Support applicant questions.
- [ ] Show confirmation after posting.
- [ ] Link posted jobs to the public jobs listing.

## Recruiting / Candidate Search

- [ ] Add a candidate discovery page for employers.
- [ ] Filter candidates by job category.
- [ ] Filter candidates by country/location.
- [ ] Filter candidates by skills.
- [ ] Filter candidates by experience level.
- [ ] Filter candidates by visa/work permit status.
- [ ] Allow employers to save candidates.
- [ ] Allow employers to shortlist candidates.
- [ ] Allow employers to contact or invite candidates to apply.
- [ ] Add permissions so employers can only see allowed candidate data.

## Applicant Management

- [ ] Add applicant list per job.
- [ ] Add applicant detail page.
- [ ] Show CV/resume link if uploaded.
- [ ] Show video profile if available.
- [ ] Show portfolio links if available.
- [ ] Add applicant statuses: new, reviewed, shortlisted, rejected, interview, hired.
- [ ] Allow employers to update applicant status.
- [ ] Allow employers to add internal notes.
- [ ] Allow employers to schedule or request an interview.
- [ ] Add applicant search/filter.

## Billing / Admin Center

- [ ] Add billing/account settings page.
- [ ] Show company account owner.
- [ ] Allow updating company account contact email.
- [ ] Add placeholders for billing plan/status.
- [ ] Add invoice history placeholder.
- [ ] Add payment method placeholder.
- [ ] Add team members placeholder.
- [ ] Add role/permission placeholders for future employer staff accounts.

## Advertising / Promotion

- [ ] Add promote job option.
- [ ] Add featured job placeholder.
- [ ] Add company page promotion placeholder.
- [ ] Add campaign list placeholder.
- [ ] Add campaign budget placeholder.
- [ ] Add campaign performance placeholder.
- [ ] Keep promotion features disabled or clearly marked until payment/billing is real.

## Premium / Growth

- [ ] Add premium tools page.
- [ ] Add placeholders for boosted reach.
- [ ] Add placeholders for advanced candidate filters.
- [ ] Add placeholders for company analytics.
- [ ] Add placeholders for applicant insights.
- [ ] Add upgrade flow placeholder.

## Learning / Training

- [ ] Add employee learning page.
- [ ] Add training/course placeholders.
- [ ] Add saved training placeholders.
- [ ] Add employee development placeholders.
- [ ] Keep it modular so real course content can be added later.

## Admin Side Requirements

- [ ] Admin can view employer accounts.
- [ ] Admin can view company pages.
- [ ] Admin can approve/reject company pages if moderation is enabled.
- [ ] Admin can view employer job posts.
- [ ] Admin can approve/reject job posts if moderation is enabled.
- [ ] Admin can manage featured/promoted jobs.
- [ ] Admin can manage business menu labels/content where appropriate.
- [ ] Admin can manage billing/premium placeholders or settings.

## Dynamic / Modular Requirements

- [ ] No hard-coded employer menu text where it should be editable.
- [ ] No hard-coded company page content.
- [ ] No hard-coded job content.
- [ ] Use database-backed models for employer business data.
- [ ] Use admin resources/settings for configurable text and options.
- [ ] Keep public pages, employer pages, and admin resources connected to the same real data.
- [ ] Make feature sections easy to enable/disable later.

## Security / Access Rules

- [ ] Employer pages require login.
- [ ] Employer pages require employer account type.
- [ ] Admin-only pages stay admin-only.
- [ ] Employers cannot edit another employer's company.
- [ ] Employers cannot view private applicant data for jobs they do not own.
- [ ] Do not show demo/admin credentials anywhere in the UI.
- [ ] Validate uploaded company logos and cover images.

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

- [ ] Employer test account with a company page.
- [ ] Employer test account without a company page.
- [ ] Candidate test account with profile/CV/video/portfolio data.
- [ ] Admin account that can inspect all employer data.

## Acceptance Checklist

- [ ] A new employer can register and reach the employer dashboard.
- [ ] The employer can create a company page.
- [ ] The employer can post a real job.
- [ ] The posted job appears publicly.
- [ ] The employer can view applicants for their own jobs.
- [ ] Candidate accounts cannot access employer tools.
- [ ] Public visitors cannot access employer private pages.
- [ ] Admin can manage employer/company/job data.
- [ ] The whole feature is data-driven and customizable.
