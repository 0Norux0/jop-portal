<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Identity\Models\User;
use App\Domain\Portal\Models\Candidate;
use App\Domain\Portal\Models\ConversationMessage;
use App\Domain\Portal\Models\Employer;
use App\Domain\Portal\Models\EmployerCreditTransaction;
use App\Domain\Portal\Models\EmployerServiceRequest;
use App\Domain\Portal\Models\Job;
use App\Domain\Portal\Models\JobApplication;
use App\Domain\Portal\Models\JobCategory;
use App\Support\EmployerEntitlements;
use App\Support\PortalData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EmployerWorkspaceController
{
    public function dashboard(Request $request): View
    {
        $employer = $this->employer($request->user());
        $jobs = $employer->jobs()->withCount('applications')->latest('updated_at')->get();
        $applications = JobApplication::query()
            ->whereHas('job', fn ($query) => $query->where('employer_id', $employer->id))
            ->latest()
            ->take(6)
            ->get();

        return view('employer.dashboard', [
            'employer' => $employer,
            'jobs' => $jobs,
            'applications' => $applications,
            'stats' => [
                'Active jobs' => $jobs->where('status', 'published')->count(),
                'Draft jobs' => $jobs->where('status', 'draft')->count(),
                'Applicants' => $jobs->sum('applications_count'),
                'Shortlisted' => JobApplication::query()
                    ->where('status', 'shortlisted')
                    ->whereHas('job', fn ($query) => $query->where('employer_id', $employer->id))
                    ->count(),
                'CV credits' => $employer->cv_access_credits,
                'Contact credits' => $employer->candidate_contact_credits,
            ],
            'entitlements' => EmployerEntitlements::forEmployer($employer),
            'serviceRequests' => $employer->serviceRequests()->latest()->take(5)->get(),
        ]);
    }

    public function company(Request $request): View
    {
        return view('employer.company', [
            'employer' => $this->employer($request->user()),
        ]);
    }

    public function updateCompany(Request $request): RedirectResponse
    {
        $employer = $this->employer($request->user());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:120'],
            'company_size' => ['nullable', 'string', 'max:120'],
            'country' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:160'],
            'contact_email' => ['nullable', 'email', 'max:160'],
            'contact_phone' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:4000'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'x_url' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'cover' => ['nullable', 'image', 'max:4096'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $employer->fill([
            'name' => $validated['name'],
            'slug' => $this->uniqueEmployerSlug($validated['name'], $employer->id),
            'industry' => $validated['industry'] ?? null,
            'company_size' => $validated['company_size'] ?? null,
            'country' => $validated['country'] ?? null,
            'city' => $validated['city'] ?? null,
            'website_url' => $validated['website_url'] ?? null,
            'contact_name' => $validated['contact_name'] ?? null,
            'contact_email' => $validated['contact_email'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_published' => (bool) ($validated['is_published'] ?? false),
            'social_links' => [
                'linkedin' => $validated['linkedin_url'] ?? null,
                'facebook' => $validated['facebook_url'] ?? null,
                'x' => $validated['x_url'] ?? null,
            ],
        ]);

        if ($request->hasFile('logo')) {
            $employer->logo_path = $this->storePublicImage($request, 'logo');
        }

        if ($request->hasFile('cover')) {
            $employer->cover_path = $this->storePublicImage($request, 'cover');
        }

        $employer->save();

        return back()->with('status', 'Company page updated.');
    }

    public function jobs(Request $request): View
    {
        $employer = $this->employer($request->user());

        return view('employer.jobs', [
            'employer' => $employer,
            'jobs' => $employer->jobs()->with(['category'])->withCount('applications')->latest('updated_at')->get(),
            'categories' => JobCategory::query()->where('is_active', true)->orderBy('name')->get(),
            'portal' => PortalData::load(),
        ]);
    }

    public function storeJob(Request $request): RedirectResponse
    {
        $employer = $this->employer($request->user());
        $validated = $this->validateJob($request);
        $status = $request->input('action') === 'publish' ? 'published' : 'draft';

        if ($status === 'published' && ! $this->canPublishMoreJobs($employer)) {
            return back()->withInput()->with('status', 'Your current employer plan has reached its published job limit. Request a package or upgrade from Paid Services.');
        }

        $employer->jobs()->create($this->jobPayload($validated, $status));

        return back()->with('status', $status === 'published' ? 'Job published publicly.' : 'Job saved as draft.');
    }

    public function updateJob(Request $request, Job $job): RedirectResponse
    {
        $employer = $this->employer($request->user());
        abort_unless($job->employer_id === $employer->id, 403);

        $validated = $this->validateJob($request);
        $status = $request->input('action') === 'publish' ? 'published' : ($request->input('status') ?: 'draft');

        if ($status === 'published' && $job->status !== 'published' && ! $this->canPublishMoreJobs($employer)) {
            return back()->withInput()->with('status', 'Your current employer plan has reached its published job limit. Request a package or upgrade from Paid Services.');
        }

        $job->update($this->jobPayload($validated, $status, $job->id));

        return back()->with('status', 'Job updated.');
    }

    public function applicants(Request $request): View
    {
        $employer = $this->employer($request->user());
        $status = $request->string('status')->toString();

        $applications = JobApplication::query()
            ->with(['job', 'candidate'])
            ->whereHas('job', fn ($query) => $query->where('employer_id', $employer->id))
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('employer.applicants', [
            'employer' => $employer,
            'applications' => $applications,
            'selectedStatus' => $status,
        ]);
    }

    public function updateApplication(Request $request, JobApplication $application): RedirectResponse
    {
        $employer = $this->employer($request->user());
        abort_unless($application->job()->where('employer_id', $employer->id)->exists(), 403);

        $validated = $request->validate([
            'status' => ['required', 'in:submitted,reviewed,shortlisted,interview,rejected,hired'],
            'internal_notes' => ['nullable', 'string', 'max:3000'],
            'request_interview' => ['nullable', 'boolean'],
        ]);

        $application->update([
            'status' => $validated['status'],
            'internal_notes' => $validated['internal_notes'] ?? null,
            'reviewed_at' => now(),
            'interview_requested_at' => ($validated['request_interview'] ?? false) ? now() : $application->interview_requested_at,
        ]);

        return back()->with('status', 'Applicant updated.');
    }

    public function messageApplicant(Request $request, JobApplication $application): RedirectResponse
    {
        $employer = $this->employer($request->user());
        abort_unless($application->job()->where('employer_id', $employer->id)->exists(), 403);
        abort_unless($application->user_id !== null, 422);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        ConversationMessage::query()->create([
            'employer_id' => $employer->id,
            'candidate_user_id' => $application->user_id,
            'job_application_id' => $application->id,
            'sender_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return back()->with('status', 'Message sent to candidate.');
    }

    public function candidates(Request $request): View
    {
        $employer = $this->employer($request->user());
        $filters = $request->only(['category', 'country', 'skill', 'visa']);
        $activeFilters = array_filter($filters, fn (mixed $value): bool => filled($value));

        if ($activeFilters !== []) {
            $signature = 'employer_candidate_search_'.$employer->id.'_'.md5(json_encode($activeFilters));

            if (! $request->session()->has($signature)) {
                if (! $this->consumeCredit($employer, 'candidate_search_credits', 'candidate_search', 'Candidate search')) {
                    return redirect()->route('business.services')->with('status', 'No candidate search credits left. Request more search credits or upgrade your plan.');
                }

                $request->session()->put($signature, true);
                $employer->refresh();
            }
        }

        $candidates = Candidate::query()
            ->when($filters['category'] ?? null, fn ($query, string $category) => $query->where('preferred_job_category', $category))
            ->when($filters['country'] ?? null, fn ($query, string $country) => $query->where('country', $country))
            ->when($filters['skill'] ?? null, fn ($query, string $skill) => $query->where('skills', 'like', '%'.$skill.'%'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('employer.candidates', [
            'employer' => $employer,
            'candidates' => $candidates,
            'filters' => $filters,
            'portal' => PortalData::load(),
            'access' => $employer->notes['candidate_access'] ?? [],
        ]);
    }

    public function inviteCandidate(Request $request, Candidate $candidate): RedirectResponse
    {
        $employer = $this->employer($request->user());

        $notes = $employer->notes ?? [];
        $saved = collect($notes['saved_candidates'] ?? [])->push([
            'candidate_id' => $candidate->id,
            'name' => $candidate->full_name,
            'saved_at' => now()->toDateTimeString(),
        ])->unique('candidate_id')->values()->all();

        $notes['saved_candidates'] = $saved;
        $employer->update(['notes' => $notes]);

        return back()->with('status', 'Candidate saved to your employer workspace.');
    }

    public function accessCandidateCv(Request $request, Candidate $candidate): RedirectResponse
    {
        $employer = $this->employer($request->user());

        if (! $candidate->cv_path) {
            return back()->with('status', 'This candidate has not uploaded a CV yet.');
        }

        if (! $this->hasCandidateAccess($employer, $candidate, 'cv')) {
            if (! $this->consumeCredit($employer, 'cv_access_credits', 'cv_access', 'CV access for '.$candidate->full_name, $candidate)) {
                return back()->with('status', 'No CV access credits left. Request more credits from Paid Services.');
            }

            $this->grantCandidateAccess($employer, $candidate, 'cv');
        }

        return redirect(asset($candidate->cv_path));
    }

    public function requestCandidateContact(Request $request, Candidate $candidate): RedirectResponse
    {
        $employer = $this->employer($request->user());

        if ($this->hasCandidateAccess($employer, $candidate, 'contact')) {
            return back()->with('status', 'Candidate contact access is already recorded for your employer account.');
        }

        if (! $this->consumeCredit($employer, 'candidate_contact_credits', 'candidate_contact', 'Contact request for '.$candidate->full_name, $candidate)) {
            return back()->with('status', 'No candidate contact credits left. Request more credits from Paid Services.');
        }

        $this->grantCandidateAccess($employer, $candidate, 'contact');
        $this->createServiceRequest($employer, 'candidate_contact', 'Candidate contact request', [
            'candidate_id' => $candidate->id,
            'candidate_name' => $candidate->full_name,
        ], candidate: $candidate);

        return back()->with('status', 'Candidate contact request created for admin review.');
    }

    public function billing(Request $request): View
    {
        $employer = $this->employer($request->user());

        return view('employer.billing', [
            'employer' => $employer,
            'plans' => EmployerEntitlements::plans(),
            'transactions' => $employer->creditTransactions()->latest()->take(10)->get(),
        ]);
    }

    public function updateBilling(Request $request): RedirectResponse
    {
        $employer = $this->employer($request->user());
        $validated = $request->validate([
            'billing_email' => ['required', 'email', 'max:160'],
            'billing_plan' => ['required', 'in:free,growth,premium,enterprise'],
        ]);

        $oldPlan = $employer->billing_plan;
        $employer->update($validated);

        if ($oldPlan !== $validated['billing_plan']) {
            EmployerEntitlements::resetCreditsForPlan($employer->refresh());
            $employer->premium_status = $validated['billing_plan'] === 'free' ? 'not_upgraded' : 'requested';
            $employer->save();

            $this->createServiceRequest($employer, 'subscription', 'Plan change request', [
                'from' => $oldPlan,
                'to' => $validated['billing_plan'],
            ]);
        }

        return back()->with('status', 'Billing settings updated.');
    }

    public function promotion(Request $request): View
    {
        return view('employer.promotion', [
            'employer' => $this->employer($request->user())->load('jobs'),
        ]);
    }

    public function storePromotion(Request $request): RedirectResponse
    {
        $employer = $this->employer($request->user());

        $validated = $request->validate([
            'job_id' => ['nullable', 'exists:job_posts,id'],
            'campaign_name' => ['required', 'string', 'max:160'],
            'goal' => ['required', 'in:more_applicants,featured_job,company_awareness'],
            'budget' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if (($validated['job_id'] ?? null) !== null) {
            abort_unless($employer->jobs()->whereKey($validated['job_id'])->exists(), 403);
        }

        $job = null;

        if (($validated['job_id'] ?? null) !== null) {
            $job = $employer->jobs()->whereKey($validated['job_id'])->first();

            if ($validated['goal'] === 'featured_job' && $job instanceof Job) {
                if (! $this->consumeCredit($employer, 'featured_job_credits', 'featured_job', 'Featured job request for '.$job->title, job: $job)) {
                    return back()->withInput()->with('status', 'No featured job credits left. Request a featured-job package from Paid Services.');
                }

                $job->update(['promotion_status' => 'requested']);
            }
        }

        $employer->update(['advertising_enabled' => true]);

        $this->createServiceRequest($employer, 'advertising', $validated['campaign_name'], $validated, $job, budget: (float) $validated['budget']);

        return back()->with('status', 'Advertisement request created for admin review.');
    }

    public function services(Request $request): View
    {
        $employer = $this->employer($request->user());

        return view('employer.services', [
            'employer' => $employer,
            'plans' => EmployerEntitlements::plans(),
            'requests' => $employer->serviceRequests()->latest()->paginate(10),
        ]);
    }

    public function requestService(Request $request): RedirectResponse
    {
        $employer = $this->employer($request->user());

        $validated = $request->validate([
            'type' => ['required', 'in:recruitment_package,premium_matching,ai_recruitment,credit_topup'],
            'title' => ['required', 'string', 'max:160'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:3000'],
        ]);

        if ($validated['type'] === 'premium_matching' && ! $this->consumeCredit($employer, 'matching_request_credits', 'premium_matching', $validated['title'])) {
            return back()->withInput()->with('status', 'No matching request credits left. Request a matching package or upgrade first.');
        }

        if ($validated['type'] === 'ai_recruitment' && ! $this->consumeCredit($employer, 'ai_recruitment_credits', 'ai_recruitment', $validated['title'])) {
            return back()->withInput()->with('status', 'No AI recruitment credits left. Request an AI package or upgrade first.');
        }

        $this->createServiceRequest(
            $employer,
            $validated['type'],
            $validated['title'],
            ['notes' => $validated['notes'] ?? null],
            budget: isset($validated['budget']) ? (float) $validated['budget'] : null,
            notes: $validated['notes'] ?? null,
        );

        return back()->with('status', 'Paid service request created for admin review.');
    }

    private function employer(?User $user): Employer
    {
        abort_unless($user instanceof User, 403);

        return Employer::query()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name.' Company',
                'slug' => $this->uniqueEmployerSlug($user->name.' Company'),
                'industry' => $user->preferred_job_category,
                'country' => $user->country,
                'city' => $user->city,
                'contact_name' => $user->name,
                'contact_email' => $user->email,
                'contact_phone' => $user->phone,
                'billing_email' => $user->email,
                'billing_plan' => 'free',
                'premium_status' => 'not_upgraded',
                'job_post_limit' => 2,
                'featured_job_credits' => 0,
                'candidate_search_credits' => 10,
                'cv_access_credits' => 1,
                'candidate_contact_credits' => 1,
                'matching_request_credits' => 0,
                'ai_recruitment_credits' => 0,
                'verification_status' => 'pending',
                'status' => 'active',
                'is_published' => false,
                'social_links' => [],
            ],
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function validateJob(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'job_category_id' => ['nullable', 'exists:job_categories,id'],
            'country' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'work_mode' => ['required', 'in:on_site,remote,hybrid'],
            'employment_type' => ['required', 'in:full_time,part_time,contract,freelance,internship'],
            'currency' => ['nullable', 'string', 'max:8'],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'salary_max' => ['nullable', 'integer', 'min:0'],
            'vacancies' => ['required', 'integer', 'min:1', 'max:1000'],
            'application_deadline' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:6000'],
            'responsibilities' => ['nullable', 'string', 'max:3000'],
            'skills' => ['nullable', 'string', 'max:3000'],
            'requirements' => ['nullable', 'string', 'max:3000'],
            'benefits' => ['nullable', 'string', 'max:3000'],
            'applicant_questions' => ['nullable', 'string', 'max:3000'],
            'visa_sponsorship' => ['nullable', 'boolean'],
            'relocation_support' => ['nullable', 'boolean'],
            'is_urgent' => ['nullable', 'boolean'],
            'status' => ['nullable', 'in:draft,published,paused,closed'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function jobPayload(array $validated, string $status, ?int $ignoreId = null): array
    {
        return [
            'title' => $validated['title'],
            'slug' => $this->uniqueJobSlug($validated['title'], $ignoreId),
            'job_category_id' => $validated['job_category_id'] ?? null,
            'country' => $validated['country'] ?? null,
            'city' => $validated['city'] ?? null,
            'work_mode' => $validated['work_mode'],
            'employment_type' => $validated['employment_type'],
            'currency' => $validated['currency'] ?? null,
            'salary_min' => $validated['salary_min'] ?? null,
            'salary_max' => $validated['salary_max'] ?? null,
            'vacancies' => $validated['vacancies'],
            'application_deadline' => $validated['application_deadline'] ?? null,
            'status' => $status,
            'published_at' => $status === 'published' ? now() : null,
            'description' => $validated['description'] ?? null,
            'responsibilities' => $this->lines($validated['responsibilities'] ?? ''),
            'skills' => $this->lines($validated['skills'] ?? ''),
            'requirements' => $this->lines($validated['requirements'] ?? ''),
            'benefits' => $this->lines($validated['benefits'] ?? ''),
            'applicant_questions' => $this->lines($validated['applicant_questions'] ?? ''),
            'visa_sponsorship' => (bool) ($validated['visa_sponsorship'] ?? false),
            'relocation_support' => (bool) ($validated['relocation_support'] ?? false),
            'is_urgent' => (bool) ($validated['is_urgent'] ?? false),
        ];
    }

    /**
     * @return array<int, string>
     */
    private function lines(string $value): array
    {
        return str($value)
            ->replace("\r", '')
            ->explode("\n")
            ->map(fn (string $line): string => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    private function uniqueEmployerSlug(string $name, ?int $ignoreId = null): string
    {
        return $this->uniqueSlug(Employer::class, $name, $ignoreId);
    }

    private function uniqueJobSlug(string $name, ?int $ignoreId = null): string
    {
        return $this->uniqueSlug(Job::class, $name, $ignoreId);
    }

    /**
     * @param  class-string<Employer|Job>  $model
     */
    private function uniqueSlug(string $model, string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'item';
        $slug = $base;
        $count = 2;

        while ($model::query()->where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$count;
            $count++;
        }

        return $slug;
    }

    private function storePublicImage(Request $request, string $field): string
    {
        $file = $request->file($field);
        abort_unless($file !== null && $file->isValid(), 422);

        $directory = public_path('company-assets');
        File::ensureDirectoryExists($directory);

        $name = Str::ulid().'.'.$file->extension();
        $file->move($directory, $name);

        return 'company-assets/'.$name;
    }

    private function canPublishMoreJobs(Employer $employer): bool
    {
        return $employer->jobs()->where('status', 'published')->count() < max(0, $employer->job_post_limit);
    }

    private function consumeCredit(Employer $employer, string $column, string $type, string $description, ?Candidate $candidate = null, ?Job $job = null): bool
    {
        $employer->refresh();

        if ((int) $employer->{$column} <= 0) {
            return false;
        }

        $employer->decrement($column);

        EmployerCreditTransaction::query()->create([
            'employer_id' => $employer->id,
            'candidate_id' => $candidate?->id,
            'job_id' => $job?->id,
            'credit_type' => $type,
            'amount' => -1,
            'description' => $description,
            'metadata' => ['balance_column' => $column],
        ]);

        return true;
    }

    private function grantCandidateAccess(Employer $employer, Candidate $candidate, string $type): void
    {
        $notes = $employer->notes ?? [];
        $access = $notes['candidate_access'] ?? [];
        $candidateAccess = $access[$candidate->public_id] ?? [];
        $candidateAccess[$type] = now()->toDateTimeString();
        $access[$candidate->public_id] = $candidateAccess;
        $notes['candidate_access'] = $access;

        $employer->update(['notes' => $notes]);
    }

    private function hasCandidateAccess(Employer $employer, Candidate $candidate, string $type): bool
    {
        return filled($employer->notes['candidate_access'][$candidate->public_id][$type] ?? null);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function createServiceRequest(Employer $employer, string $type, string $title, array $payload = [], ?Job $job = null, ?Candidate $candidate = null, ?float $budget = null, ?string $notes = null): EmployerServiceRequest
    {
        return EmployerServiceRequest::query()->create([
            'employer_id' => $employer->id,
            'job_id' => $job?->id,
            'candidate_id' => $candidate?->id,
            'type' => $type,
            'title' => $title,
            'status' => 'requested',
            'budget' => $budget,
            'payload' => $payload,
            'notes' => $notes,
        ]);
    }
}
