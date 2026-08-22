<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Identity\Models\User;
use App\Domain\Portal\Models\Candidate;
use App\Domain\Portal\Models\CandidateCertificate;
use App\Domain\Portal\Models\CandidateEducation;
use App\Domain\Portal\Models\CandidateExperience;
use App\Domain\Portal\Models\CandidatePortfolioItem;
use App\Domain\Portal\Models\CandidateProject;
use App\Domain\Portal\Models\ConversationMessage;
use App\Domain\Portal\Models\Job;
use App\Domain\Portal\Models\JobAlert;
use App\Domain\Portal\Models\JobApplication;
use App\Domain\Portal\Models\SavedJob;
use App\Support\CountryRepository;
use App\Support\PortalData;
use App\Support\PortalJobPresenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CandidateWorkspaceController
{
    public function dashboard(Request $request): View
    {
        $user = $this->user($request);
        $candidate = $this->candidate($user);

        $jobs = collect(PortalJobPresenter::publishedJobs());
        $preferredCategory = (string) $candidate->preferred_job_category;
        $preferredCountries = collect($candidate->preferred_locations ?? [])->filter()->values();
        $recommendedJobs = $jobs
            ->sortByDesc(function (array $job) use ($candidate, $preferredCategory, $preferredCountries): int {
                return ($preferredCategory !== '' && ($job['category'] ?? '') === $preferredCategory ? 60 : 0)
                    + ($preferredCountries->contains($job['country'] ?? '') ? 35 : 0)
                    + (filled($candidate->country) && ($job['country'] ?? '') === $candidate->country ? 20 : 0)
                    + (filled($candidate->work_mode_preference) && str_contains(strtolower((string) ($job['mode'] ?? '')), strtolower(str_replace('_', '-', (string) $candidate->work_mode_preference))) ? 10 : 0);
            })
            ->values()
            ->take(3)
            ->all();

        return view('dashboard', [
            'portal' => ['jobs' => $jobs->all()],
            'recommendedJobs' => $recommendedJobs,
            'candidate' => $candidate,
            'profileCompletion' => $this->completion($candidate),
            'alerts' => $user->jobAlerts()->latest()->take(3)->get(),
            'savedJobs' => $this->savedJobCollection($user)->take(2),
            'applications' => $this->applicationCollection($user)->take(2),
            'messages' => $this->messageCollection($user)->take(3),
        ]);
    }

    public function profile(Request $request): View
    {
        $candidate = $this->candidate($this->user($request))->load([
            'portfolioItems',
            'projects',
            'certificates',
            'educations',
            'experiences',
        ]);

        return view('candidate.profile', [
            'candidate' => $candidate,
            'portal' => PortalData::load(),
            'nationalities' => CountryRepository::nationalities(),
            'completion' => $this->completion($candidate),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $this->user($request);
        $candidate = $this->candidate($user);
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:160'],
            'headline' => ['nullable', 'string', 'max:180'],
            'bio' => ['nullable', 'string', 'max:4000'],
            'email' => ['nullable', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:80'],
            'country' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'current_job_title' => ['nullable', 'string', 'max:160'],
            'preferred_job_category' => ['nullable', 'string', 'max:160'],
            'preferred_locations' => ['nullable', 'array'],
            'preferred_locations.*' => ['string', 'max:120'],
            'employment_type_preference' => ['nullable', 'string', 'max:80'],
            'work_mode_preference' => ['nullable', 'string', 'max:80'],
            'work_authorization' => ['nullable', 'string', 'max:160'],
            'visa_requirements' => ['nullable', 'string', 'max:160'],
            'relocation_preference' => ['nullable', 'string', 'max:160'],
            'expected_salary' => ['nullable', 'string', 'max:120'],
            'notice_period' => ['nullable', 'string', 'max:120'],
            'availability_status' => ['nullable', 'string', 'max:80'],
            'skills' => ['nullable', 'string', 'max:2000'],
            'languages' => ['nullable', 'string', 'max:1000'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'personal_website_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'behance_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'is_public' => ['nullable', 'boolean'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:8192'],
            'video' => ['nullable', 'file', 'mimes:mp4,mov,webm', 'max:51200'],
        ]);

        $candidate->fill([
            'full_name' => $validated['full_name'],
            'slug' => $candidate->slug ?: $this->uniqueCandidateSlug($validated['full_name']),
            'headline' => $validated['headline'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'email' => $validated['email'] ?? $user->email,
            'phone' => $validated['phone'] ?? null,
            'country' => $validated['country'] ?? null,
            'city' => $validated['city'] ?? null,
            'current_job_title' => $validated['current_job_title'] ?? null,
            'preferred_job_category' => $validated['preferred_job_category'] ?? null,
            'preferred_locations' => array_values($validated['preferred_locations'] ?? []),
            'employment_type_preference' => $validated['employment_type_preference'] ?? null,
            'work_mode_preference' => $validated['work_mode_preference'] ?? null,
            'work_authorization' => $validated['work_authorization'] ?? null,
            'visa_requirements' => $validated['visa_requirements'] ?? null,
            'relocation_preference' => $validated['relocation_preference'] ?? null,
            'expected_salary' => $validated['expected_salary'] ?? null,
            'notice_period' => $validated['notice_period'] ?? null,
            'availability_status' => $validated['availability_status'] ?? 'open_to_work',
            'skills' => $this->csv($validated['skills'] ?? ''),
            'languages' => $this->csv($validated['languages'] ?? ''),
            'linkedin_url' => $validated['linkedin_url'] ?? null,
            'portfolio_url' => $validated['portfolio_url'] ?? null,
            'is_public' => (bool) ($validated['is_public'] ?? false),
            'external_links' => array_filter([
                'personal_website' => $validated['personal_website_url'] ?? null,
                'github' => $validated['github_url'] ?? null,
                'behance' => $validated['behance_url'] ?? null,
                'youtube' => $validated['youtube_url'] ?? null,
            ]),
        ]);

        if ($request->hasFile('cv')) {
            $candidate->cv_path = $this->storeCandidateFile($request, 'cv');
        }

        if ($request->hasFile('video')) {
            $candidate->video_path = $this->storeCandidateFile($request, 'video');
        }

        $candidate->trust_score = $this->completion($candidate)['percent'];
        $candidate->save();

        return back()->with('status', 'Profile updated.');
    }

    public function publicProfile(string $slug): View
    {
        $candidate = Candidate::query()
            ->with(['portfolioItems', 'projects', 'certificates', 'educations', 'experiences'])
            ->where('slug', $slug)
            ->where('is_public', true)
            ->first();

        abort_unless($candidate, 404);

        return view('candidate.public-profile', [
            'candidate' => $candidate,
            'completion' => $this->completion($candidate),
        ]);
    }

    public function saveJob(Request $request, Job $job): RedirectResponse
    {
        abort_unless($job->status === 'published', 404);

        SavedJob::query()->firstOrCreate(
            ['user_id' => $this->user($request)->id, 'job_id' => $job->id],
            ['saved_at' => now()],
        );

        return back()->with('status', 'Job saved.');
    }

    public function unsaveJob(Request $request, SavedJob $savedJob): RedirectResponse
    {
        abort_unless($savedJob->user_id === $this->user($request)->id, 403);

        $savedJob->delete();

        return back()->with('status', 'Saved job removed.');
    }

    public function apply(Request $request, Job $job): RedirectResponse
    {
        abort_unless($job->status === 'published', 404);

        $user = $this->user($request);
        $candidate = $this->candidate($user);
        $validated = $request->validate([
            'cover_letter' => ['nullable', 'string', 'max:3000'],
        ]);

        JobApplication::query()->firstOrCreate(
            ['job_id' => $job->id, 'user_id' => $user->id],
            [
                'candidate_id' => $candidate?->id,
                'candidate_name' => $user->name,
                'candidate_email' => $user->email,
                'method' => 'portal_profile',
                'status' => 'submitted',
                'linkedin_url' => $user->linkedin_url,
                'cover_letter' => $validated['cover_letter'] ?? null,
            ],
        );

        return redirect()->route('candidate.applications')->with('status', 'Application submitted.');
    }

    public function savedJobs(Request $request): View
    {
        return view('candidate.saved-jobs', [
            'savedJobs' => $this->savedJobCollection($this->user($request)),
        ]);
    }

    public function applications(Request $request): View
    {
        return view('candidate.applications', [
            'applications' => $this->applicationCollection($this->user($request)),
        ]);
    }

    public function messages(Request $request): View
    {
        $user = $this->user($request);
        $messages = $this->messageCollection($user);

        ConversationMessage::query()
            ->where('candidate_user_id', $user->id)
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('candidate.messages', ['messages' => $messages]);
    }

    public function sendMessage(Request $request): RedirectResponse
    {
        $user = $this->user($request);
        $validated = $request->validate([
            'employer_id' => ['required', 'exists:employers,id'],
            'job_application_id' => ['nullable', 'exists:job_applications,id'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $applicationId = $validated['job_application_id'] ?? null;

        if ($applicationId !== null) {
            abort_unless(JobApplication::query()->whereKey($applicationId)->where('user_id', $user->id)->exists(), 403);
        }

        ConversationMessage::query()->create([
            'employer_id' => $validated['employer_id'],
            'candidate_user_id' => $user->id,
            'job_application_id' => $applicationId,
            'sender_id' => $user->id,
            'body' => $validated['body'],
        ]);

        return back()->with('status', 'Message sent.');
    }

    public function storePortfolio(Request $request): RedirectResponse
    {
        $candidate = $this->candidate($this->user($request));
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'type' => ['required', 'in:link,file,image,video'],
            'url' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'file' => ['nullable', 'file', 'max:10240'],
        ]);

        $candidate->portfolioItems()->create([
            ...$validated,
            'file_path' => $request->hasFile('file') ? $this->storeCandidateFile($request, 'file') : null,
        ]);

        return back()->with('status', 'Portfolio item added.');
    }

    public function storeProject(Request $request): RedirectResponse
    {
        $candidate = $this->candidate($this->user($request));
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'role' => ['nullable', 'string', 'max:160'],
            'url' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:3000'],
            'skills' => ['nullable', 'string', 'max:1000'],
            'started_on' => ['nullable', 'date'],
            'ended_on' => ['nullable', 'date'],
        ]);

        $validated['skills'] = $this->csv($validated['skills'] ?? '');

        $candidate->projects()->create($validated);

        return back()->with('status', 'Project added.');
    }

    public function storeCertificate(Request $request): RedirectResponse
    {
        $candidate = $this->candidate($this->user($request));
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'issuer' => ['nullable', 'string', 'max:160'],
            'credential_number' => ['nullable', 'string', 'max:160'],
            'credential_url' => ['nullable', 'url', 'max:255'],
            'issued_on' => ['nullable', 'date'],
            'expires_on' => ['nullable', 'date'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:8192'],
        ]);

        $candidate->certificates()->create([
            ...$validated,
            'file_path' => $request->hasFile('file') ? $this->storeCandidateFile($request, 'file') : null,
            'verification_status' => 'uploaded',
        ]);

        return back()->with('status', 'Certificate added.');
    }

    public function storeEducation(Request $request): RedirectResponse
    {
        $candidate = $this->candidate($this->user($request));
        $validated = $request->validate([
            'school' => ['required', 'string', 'max:180'],
            'degree' => ['nullable', 'string', 'max:160'],
            'field' => ['nullable', 'string', 'max:160'],
            'started_on' => ['nullable', 'date'],
            'ended_on' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $candidate->educations()->create($validated);

        return back()->with('status', 'Education added.');
    }

    public function storeExperience(Request $request): RedirectResponse
    {
        $candidate = $this->candidate($this->user($request));
        $validated = $request->validate([
            'company' => ['required', 'string', 'max:180'],
            'title' => ['required', 'string', 'max:160'],
            'location' => ['nullable', 'string', 'max:160'],
            'started_on' => ['nullable', 'date'],
            'ended_on' => ['nullable', 'date'],
            'is_current' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string', 'max:3000'],
        ]);

        $candidate->experiences()->create($validated + ['is_current' => (bool) ($validated['is_current'] ?? false)]);

        return back()->with('status', 'Experience added.');
    }

    public function destroyProfileItem(Request $request, string $type, string $publicId): RedirectResponse
    {
        $candidate = $this->candidate($this->user($request));
        $map = [
            'portfolio' => CandidatePortfolioItem::class,
            'project' => CandidateProject::class,
            'certificate' => CandidateCertificate::class,
            'education' => CandidateEducation::class,
            'experience' => CandidateExperience::class,
        ];

        abort_unless(isset($map[$type]), 404);

        $item = $map[$type]::query()->where('public_id', $publicId)->where('candidate_id', $candidate->id)->firstOrFail();
        $item->delete();

        return back()->with('status', 'Profile item removed.');
    }

    public function storeAlert(Request $request): RedirectResponse
    {
        $user = $this->user($request);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'keyword' => ['nullable', 'string', 'max:120'],
            'country' => ['nullable', 'string', 'max:120'],
            'category' => ['nullable', 'string', 'max:120'],
            'frequency' => ['required', 'in:daily,weekly,monthly'],
        ]);

        $user->jobAlerts()->create($validated + ['is_active' => true]);

        return back()->with('status', 'Job alert created.');
    }

    public function destroyAlert(Request $request, JobAlert $alert): RedirectResponse
    {
        abort_unless($alert->user_id === $this->user($request)->id, 403);

        $alert->delete();

        return back()->with('status', 'Job alert removed.');
    }

    private function user(Request $request): User
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        return $user;
    }

    private function candidate(User $user): Candidate
    {
        return Candidate::query()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $user->name,
                'slug' => $this->uniqueCandidateSlug($user->name),
                'email' => $user->email,
                'phone' => $user->phone,
                'country' => $user->country,
                'city' => $user->city,
                'current_job_title' => $user->current_job_title,
                'preferred_job_category' => $user->preferred_job_category,
                'preferred_locations' => $user->preferred_work_countries ?? [],
                'employment_type_preference' => null,
                'work_mode_preference' => $user->available_for_remote_work ? 'remote' : null,
                'work_authorization' => $user->visa_work_permit_status,
                'visa_requirements' => null,
                'relocation_preference' => $user->willing_to_relocate ? 'willing_to_relocate' : null,
                'linkedin_url' => $user->linkedin_url,
                'portfolio_url' => $user->portfolio_url,
                'external_links' => array_filter([
                    'personal_website' => $user->personal_website_url,
                    'github' => $user->github_url,
                    'behance' => $user->behance_url,
                    'youtube' => $user->youtube_url,
                ]),
                'verification_status' => 'unverified',
                'availability_status' => 'open_to_work',
                'trust_score' => 20,
                'skills' => [],
            ],
        );
    }

    private function savedJobCollection(User $user)
    {
        return SavedJob::query()
            ->with(['job.employer', 'job.category'])
            ->where('user_id', $user->id)
            ->latest('saved_at')
            ->get();
    }

    private function applicationCollection(User $user)
    {
        return JobApplication::query()
            ->with(['job.employer'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();
    }

    private function messageCollection(User $user)
    {
        return ConversationMessage::query()
            ->with(['employer', 'sender', 'application.job'])
            ->where('candidate_user_id', $user->id)
            ->latest()
            ->get();
    }

    /**
     * @return array{percent: int, missing: array<int, string>}
     */
    private function completion(Candidate $candidate): array
    {
        $candidate->loadMissing(['portfolioItems', 'projects', 'certificates', 'educations', 'experiences']);

        $checks = [
            'Add a headline' => filled($candidate->headline),
            'Add a bio' => filled($candidate->bio),
            'Upload CV' => filled($candidate->cv_path),
            'Upload video profile' => filled($candidate->video_path),
            'Add skills' => count($candidate->skills ?? []) > 0,
            'Add languages' => count($candidate->languages ?? []) > 0,
            'Add work preferences' => filled($candidate->work_mode_preference) || filled($candidate->employment_type_preference),
            'Add preferred locations' => count($candidate->preferred_locations ?? []) > 0,
            'Add portfolio item' => $candidate->portfolioItems->isNotEmpty(),
            'Add project' => $candidate->projects->isNotEmpty(),
            'Add certificate' => $candidate->certificates->isNotEmpty(),
            'Add education' => $candidate->educations->isNotEmpty(),
            'Add experience' => $candidate->experiences->isNotEmpty(),
        ];

        $done = collect($checks)->filter()->count();

        return [
            'percent' => (int) round(($done / count($checks)) * 100),
            'missing' => collect($checks)->filter(fn (bool $done): bool => ! $done)->keys()->values()->all(),
        ];
    }

    /**
     * @return array<int, string>
     */
    private function csv(string $value): array
    {
        return str($value)
            ->replace("\r", '')
            ->explode(',')
            ->map(fn (string $item): string => trim($item))
            ->filter()
            ->values()
            ->all();
    }

    private function storeCandidateFile(Request $request, string $field): string
    {
        $file = $request->file($field);
        abort_unless($file !== null && $file->isValid(), 422);

        $directory = public_path('candidate-assets');
        File::ensureDirectoryExists($directory);

        $name = Str::ulid().'.'.$file->extension();
        $file->move($directory, $name);

        return 'candidate-assets/'.$name;
    }

    private function uniqueCandidateSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'candidate';
        $slug = $base;
        $count = 2;

        while (Candidate::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$count;
            $count++;
        }

        return $slug;
    }
}
