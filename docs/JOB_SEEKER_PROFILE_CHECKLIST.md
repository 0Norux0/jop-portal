# Job Seeker Profile Completion Checklist

Last updated: 2026-08-11

## Goal

Build the job seeker side as a real, mostly free candidate workspace. Job seekers should be able to create a professional profile, manage career records, upload files, apply to jobs, save jobs, and receive job alerts.

## Required Features

- [x] Candidate can edit core profile details.
- [x] Candidate can upload and replace CV/resume.
- [x] Candidate can upload and replace video profile.
- [x] Candidate can add portfolio links/items.
- [x] Candidate can add projects.
- [x] Candidate can add certificates.
- [x] Candidate can add education records.
- [x] Candidate can add work experience records.
- [x] Candidate can add and edit skills.
- [x] Candidate can manage external professional profile links.
- [x] Candidate gets an online public professional profile.
- [x] Candidate can search jobs.
- [x] Candidate can apply to jobs.
- [x] Candidate can save jobs.
- [x] Candidate can create job alerts.
- [x] Candidate dashboard shows real profile completion.
- [x] Candidate dashboard links point to real candidate tools.
- [x] Employers can see candidate CV/portfolio/video/profile data where appropriate.

## Already Present Before This Pass

- [x] Candidate registration exists.
- [x] Job search exists.
- [x] Job applications exist.
- [x] Saved jobs exist.
- [x] Basic candidate dashboard exists.
- [x] Basic employer messaging exists.
- [x] Registration stores external links.
- [x] Candidate model has basic profile fields and skills.

## Implementation Notes

- Keep job seeker core features free.
- Do not expose internal roadmap/future-module hints on public pages.
- Store uploaded candidate files in `public/candidate-assets`.
- Keep sensitive/private candidate files behind normal application access later if private storage is added.
- The first implementation should be database-backed and usable, even if advanced moderation/verification comes later.

## Completed In This Pass

- Added database tables for portfolio items, projects, certificates, education, experience, and job alerts.
- Added extra candidate fields for public profile slug, video upload, salary expectation, notice period, public visibility, and external links.
- Added candidate profile manager at `/profile`.
- Added public professional profile at `/talent/{slug}`.
- Added CV and video upload controls.
- Added repeatable forms for experience, education, certificates, projects, and portfolio items.
- Added job alert creation/removal.
- Added `job-alerts:send` command and daily schedule entry for job alert delivery.
- Updated dashboard profile completion to use real candidate data.
- Updated old signed-in candidate tool links to point to the real profile manager.
- Updated employer candidate/applicant screens to expose available CV/video/portfolio/profile assets.
- Ran the migrations successfully on MariaDB/XAMPP.

## Still Future Enhancements

- Mail transport configuration for real email delivery outside the local machine.
- Admin verification workflow for certificates and uploaded videos.
- Private file storage instead of public file URLs.
- Rich inline editing for existing profile items.
- Profile view analytics.
