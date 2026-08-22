# Errors And Fix Checklist

Source: `C:\Users\arnol\Documents\errors.docx`

## Visual / Global UI

- [ ] Inspect the embedded screenshots from the doc and add any visual-only issues not captured in text.
  - Note: extracted screenshots are in `extracted-error-images/`, and the DOCX XML image order/context was inspected. Local image viewing/OCR still failed in this session, so screenshot-only visual details still need direct review.
- [x] Make icons circular where the UI currently uses non-circular icon containers.
- [x] Add useful hints/placeholders to search bars.
- [x] Remove global/header search bars from sign-in, sign-up, and any other pages where the search UI appears out of place.
- [x] Improve the Dashboard and Sign out buttons visually.
- [x] Rework admin styling so it feels cleaner and less gloomy.
- [x] Update country lists everywhere to use a complete country list.
- [x] Create one shared general countries source that all country dropdowns can use.

## Authentication / Registration

- [x] Fix the 403 error when users verify email from a phone.
- [x] Rework the register page because it is too long and bulky.
- [x] Replace generic “Create an account” wording with clearer choices such as “Find a job” and “Post a job” / employer-focused wording.
- [x] Make the registration flow closer to the referenced Indeed-style screenshot.
  - Note: completed from the doc text/XML context by making signup compact, choice-first, and minimum-fields-first.
- [x] For job seekers, collect only the minimum required fields during sign-up and move the rest to profile completion.

## Employer Side

- [x] Fix the employer dashboard so it shows the signed-in employer’s actual data instead of demo details.
- [x] Prevent employer pages from showing to users who are not signed in as employers.
- [x] Fix all related employer authorization/access issues.
- [x] Fix “Trusted hiring teams across regions” so it links to the actual employer page instead of the contact page.
- [x] Remove or disable anything credit/plan-related until limitations are implemented later.
- [x] Fix Recent Applicants on the employer side so it is clickable/usable.
- [x] Fix the 500 error on `/business/candidates`.
- [x] Remove Advertise and Paid Services pages/links from the employer side.

## Job Search / Candidate Search

- [x] Fix global job search so it actually searches correctly.
- [x] Make global job search less bulky.
- [x] Move global job search filters behind a “Filter” button.
- [x] Add the same improved filter styling to candidate search.
- [x] Fix candidate search country filters so country options are not blank.
- [x] Make recommended jobs update dynamically instead of appearing static.
- [x] Remove Hire Talent, Upload CV, and Post a Job buttons/links from job-seeker search surfaces where they are not needed.

## Reports / Safety

- [x] Fix reported jobs so a report is tied only to the selected job.
- [x] Prevent users from editing the job being reported.
- [x] Ensure submitted job reports appear in the admin side.
- [x] Let admins see the submitted report message.
- [x] Let admins reply to job reports if that is part of the intended flow.
- [x] Remove the Trust and Safety page.

## Admin / Activity

- [x] Remove IP address display from login activity.
- [x] Fix maintenance mode page switching error: “Error while loading page. There was an error while attempting to load this page. Please try again later.”

## Candidate Side / Profile

- [x] Add an optional YouTube link field for video CVs.
- [x] Fix adding Experience entries.
- [x] Fix adding Education entries.
- [x] Fix adding Certificates entries.
- [x] Fix adding Projects entries.
- [x] Fix adding Portfolio entries.
- [x] Add a fill chooser so candidates can choose which profile section to fill.
- [x] Reduce Edit Professional Profile to required or useful information only.

## Resume Builder

- [x] Fix resume builder drag-and-drop upload so dropping a file uploads it instead of downloading it.
- [x] Fix resume builder click-to-upload if it is not opening the file picker.

## Public Pages

- [x] Rework the contact page into a useful, real contact page.
- [x] Rework the About Us page completely.


