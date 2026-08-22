<?php

declare(strict_types=1);

use App\Support\PortalData;
use App\Support\PortalJobPresenter;
use App\Support\PortalReports;
use App\Support\CareerCoach;
use App\Support\EmailContent;
use App\Support\InstitutionMetrics;
use App\Http\Controllers\CandidateWorkspaceController;
use App\Http\Controllers\EmployerWorkspaceController;
use App\Domain\Identity\Enums\Role;
use App\Domain\Portal\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', static fn () => view('welcome', ['portal' => PortalData::load()]))->name('home');
Route::get('/about-us', static fn () => view('portal.about-us', ['portal' => PortalData::load()]))->name('about-us');
Route::get('/contact', static fn () => view('portal.contact', ['portal' => PortalData::load()]))->name('contact');

Route::middleware('portal.capability:candidates')->group(function (): void {
    Route::get('/job-seekers', static fn () => view('portal.job-seekers', ['portal' => PortalData::load()]))->name('job-seekers');
    Route::get('/candidate-profile', static fn () => Auth::check() ? redirect()->route('candidate.profile') : view('portal.candidate-profile', ['portal' => PortalData::load()]))->name('candidate-profile');
    Route::get('/cv-builder', static fn () => Auth::check() ? redirect()->route('candidate.profile') : view('portal.cv-builder', ['portal' => PortalData::load()]))->name('cv-builder');
    Route::get('/video-profile', static fn () => Auth::check() ? redirect()->route('candidate.profile') : view('portal.video-profile', ['portal' => PortalData::load()]))->name('video-profile');
    Route::get('/portfolio', static fn () => Auth::check() ? redirect()->route('candidate.profile') : view('portal.portfolio', ['portal' => PortalData::load()]))->name('portfolio');
});

Route::middleware('portal.capability:employers')->group(function (): void {
    Route::get('/employers', static fn () => view('portal.employers', ['portal' => PortalData::load()]))->name('employers');
    Route::get('/employer-register', static fn () => view('portal.employer-register', ['portal' => PortalData::load()]))->name('employer-register');
    Route::get('/employer-dashboard', static function (Request $request) {
        if ($request->user()?->hasAnyRole([Role::Employer->value, Role::RecruitmentAgency->value])) {
            return redirect()->route('business.dashboard');
        }

        return redirect()->route('login');
    })->name('employer-dashboard');
    Route::get('/candidate-search', static function (Request $request) {
        $portal = PortalData::load();
        $filters = [
            'q' => trim((string) $request->query('q', '')),
            'country' => trim((string) $request->query('country', '')),
            'badge' => trim((string) $request->query('badge', '')),
        ];

        $portal['candidates'] = collect($portal['candidates'])
            ->when($filters['q'] !== '', fn ($candidates) => $candidates->filter(function (array $candidate) use ($filters): bool {
                $haystack = strtolower(implode(' ', [
                    $candidate['name'] ?? '',
                    $candidate['headline'] ?? '',
                    $candidate['country'] ?? '',
                    implode(' ', $candidate['skills'] ?? []),
                ]));

                return str_contains($haystack, strtolower($filters['q']));
            }))
            ->when($filters['country'] !== '', fn ($candidates) => $candidates->where('country', $filters['country']))
            ->when($filters['badge'] !== '', fn ($candidates) => $candidates->filter(fn (array $candidate): bool => in_array($filters['badge'], $candidate['badges'] ?? [], true)))
            ->values()
            ->all();

        return view('portal.candidate-search', ['portal' => $portal, 'filters' => $filters]);
    })->name('candidate-search');
    Route::get('/packages', static fn () => abort(404))->name('packages');
});

Route::middleware('portal.capability:content')->group(function (): void {
    Route::get('/career-resources', static fn () => view('portal.blog', ['portal' => PortalData::load()]))->name('blog');
    Route::get('/career-coach', function (Request $request) {
        $input = $request->only(['target_role', 'skills', 'has_cv', 'has_portfolio', 'has_video']);

        return view('portal.career-coach', [
            'portal' => PortalData::load(),
            'input' => $input,
            'advice' => filled($input['target_role'] ?? '') ? CareerCoach::advise($input) : null,
        ]);
    })->name('career-coach');
});

Route::middleware('portal.capability:international')->group(function (): void {
    Route::get('/international-support', static fn () => view('portal.international-support', ['portal' => PortalData::load()]))->name('international-support');
});

Route::middleware('portal.capability:content')->group(function (): void {
    Route::get('/institution-dashboards', static fn () => view('portal.institution-dashboards', [
        'portal' => PortalData::load(),
        'dashboards' => InstitutionMetrics::dashboards(),
    ]))->name('institution-dashboards');

    Route::get('/institution-dashboards/{slug}', function (string $slug) {
        $dashboard = InstitutionMetrics::find($slug);

        abort_unless($dashboard, 404);

        return view('portal.institution-dashboard-show', [
            'portal' => PortalData::load(),
            'dashboard' => $dashboard,
        ]);
    })->name('institution-dashboard.show');
});

Route::middleware('portal.capability:trust_safety')->group(function (): void {
    Route::get('/trust-safety', static fn () => abort(404))->name('trust-safety');
    Route::get('/platform-admin', static fn () => view('portal.platform-admin', ['portal' => PortalData::load()]))->name('platform-admin');
    Route::get('/candidate-verification', static fn () => view('portal.candidate-verification', ['portal' => PortalData::load()]))->name('candidate-verification');
    Route::get('/employer-verification', static fn () => view('portal.employer-verification', ['portal' => PortalData::load()]))->name('employer-verification');
    Route::get('/report/{type}/{subject?}', function (string $type, ?string $subject = null) {
        abort_unless(in_array($type, ['job', 'employer', 'candidate'], true), 404);

        return view('portal.report', [
            'portal' => PortalData::load(),
            'type' => $type,
            'subject' => $subject ?? ucfirst($type),
            'reasons' => PortalReports::reasons(),
        ]);
    })->name('report.create');
    Route::post('/report', function (Request $request) {
        $validated = $request->validate([
            'type' => ['required', 'in:job,employer,candidate'],
            'subject' => ['required', 'string', 'max:160'],
            'reason' => ['required', 'string', 'max:160'],
            'description' => ['required', 'string', 'min:10', 'max:3000'],
            'contact_email' => ['nullable', 'email', 'max:160'],
        ]);

        $validated['reporter_id'] = $request->user()?->id;
        $report = PortalReports::create($validated);

        return redirect()->route('jobs.index')->with('status', 'Report submitted for admin review. Reference: '.$report['id']);
    })->name('report.store');
});

Route::middleware('portal.capability:jobs')->group(function (): void {
    Route::get('/jobs', static function (Request $request) {
        $portal = PortalData::load();
        $filters = [
            'q' => trim((string) $request->query('q', '')),
            'country' => trim((string) $request->query('country', '')),
            'category' => trim((string) $request->query('category', '')),
            'work_mode' => trim((string) $request->query('work_mode', '')),
            'employment_type' => trim((string) $request->query('employment_type', '')),
            'visa' => (string) $request->query('visa', ''),
            'urgent' => (string) $request->query('urgent', ''),
        ];

        $portal['jobs'] = collect(PortalJobPresenter::publishedJobs())
            ->when($filters['q'] !== '', fn ($jobs) => $jobs->filter(function (array $job) use ($filters): bool {
                $haystack = strtolower(implode(' ', [
                    $job['title'] ?? '',
                    $job['company'] ?? '',
                    $job['city'] ?? '',
                    $job['country'] ?? '',
                    $job['category'] ?? '',
                    $job['description'] ?? '',
                    implode(' ', $job['skills'] ?? []),
                ]));

                return str_contains($haystack, strtolower($filters['q']));
            }))
            ->when($filters['country'] !== '', fn ($jobs) => $jobs->where('country', $filters['country']))
            ->when($filters['category'] !== '', fn ($jobs) => $jobs->where('category', $filters['category']))
            ->when($filters['work_mode'] !== '', fn ($jobs) => $jobs->filter(fn (array $job): bool => strtolower((string) $job['mode']) === strtolower(str_replace('_', '-', $filters['work_mode']))))
            ->when($filters['employment_type'] !== '', fn ($jobs) => $jobs->filter(fn (array $job): bool => strtolower((string) $job['type']) === strtolower(str_replace('_', '-', $filters['employment_type']))))
            ->when($filters['visa'] === '1', fn ($jobs) => $jobs->filter(fn (array $job): bool => in_array('Visa sponsorship', $job['badges'] ?? [], true)))
            ->when($filters['urgent'] === '1', fn ($jobs) => $jobs->where('urgent', true))
            ->values()
            ->all();

        return view('portal.jobs.index', ['portal' => $portal, 'filters' => $filters]);
    })->name('jobs.index');
    Route::get('/jobs/{slug}', function (string $slug) {
        $portal = PortalData::load();
        $job = PortalJobPresenter::find($slug);

        abort_unless($job, 404);

        $portal['jobs'] = PortalJobPresenter::publishedJobs();

        return view('portal.jobs.show', ['portal' => $portal, 'job' => $job, 'similarJobs' => PortalJobPresenter::similar($slug, $job['category'] ?? null)]);
    })->name('jobs.show');
});

Route::get('/companies/{slug}', function (string $slug) {
    $employer = Employer::query()
        ->with(['jobs' => fn ($query) => $query->where('status', 'published')->latest()])
        ->where('slug', $slug)
        ->where('is_published', true)
        ->first();

    abort_unless($employer, 404);

    return view('portal.company-show', ['employer' => $employer]);
})->name('companies.show');

Route::get('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout.safe');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/dashboard', function (Request $request) {
        if ($request->user()?->hasAnyRole([Role::Employer->value, Role::RecruitmentAgency->value])) {
            return redirect()->route('business.dashboard');
        }

        return app(CandidateWorkspaceController::class)->dashboard($request);
    })->name('dashboard');
    Route::post('/jobs/{job:slug}/save', [CandidateWorkspaceController::class, 'saveJob'])->name('candidate.jobs.save');
    Route::delete('/saved-jobs/{savedJob}', [CandidateWorkspaceController::class, 'unsaveJob'])->name('candidate.saved-jobs.destroy');
    Route::post('/jobs/{job:slug}/apply', [CandidateWorkspaceController::class, 'apply'])->name('candidate.jobs.apply');
    Route::get('/profile', [CandidateWorkspaceController::class, 'profile'])->name('candidate.profile');
    Route::post('/profile', [CandidateWorkspaceController::class, 'updateProfile'])->name('candidate.profile.update');
    Route::post('/profile/portfolio', [CandidateWorkspaceController::class, 'storePortfolio'])->name('candidate.profile.portfolio.store');
    Route::post('/profile/projects', [CandidateWorkspaceController::class, 'storeProject'])->name('candidate.profile.projects.store');
    Route::post('/profile/certificates', [CandidateWorkspaceController::class, 'storeCertificate'])->name('candidate.profile.certificates.store');
    Route::post('/profile/education', [CandidateWorkspaceController::class, 'storeEducation'])->name('candidate.profile.education.store');
    Route::post('/profile/experience', [CandidateWorkspaceController::class, 'storeExperience'])->name('candidate.profile.experience.store');
    Route::delete('/profile/{type}/{publicId}', [CandidateWorkspaceController::class, 'destroyProfileItem'])->name('candidate.profile.items.destroy');
    Route::post('/job-alerts', [CandidateWorkspaceController::class, 'storeAlert'])->name('candidate.alerts.store');
    Route::delete('/job-alerts/{alert}', [CandidateWorkspaceController::class, 'destroyAlert'])->name('candidate.alerts.destroy');
    Route::get('/saved-jobs', [CandidateWorkspaceController::class, 'savedJobs'])->name('candidate.saved-jobs');
    Route::get('/applications', [CandidateWorkspaceController::class, 'applications'])->name('candidate.applications');
    Route::get('/messages', [CandidateWorkspaceController::class, 'messages'])->name('candidate.messages');
    Route::post('/messages', [CandidateWorkspaceController::class, 'sendMessage'])->name('candidate.messages.store');
});

Route::get('/talent/{slug}', [CandidateWorkspaceController::class, 'publicProfile'])->name('candidate.public');

Route::middleware(['auth', 'verified', 'admin.section:email-previews'])
    ->prefix('email-previews')
    ->name('email-previews.')
    ->group(function (): void {
        Route::get('/', function () {
            return view('emails.preview-index', [
                'previews' => [
                    ['label' => 'Forgot password email', 'url' => route('email-previews.show', 'reset-password')],
                    ['label' => 'Email confirmation', 'url' => route('email-previews.show', 'verify-email')],
                    ['label' => 'Welcome email', 'url' => route('email-previews.show', 'welcome')],
                ],
            ]);
        })->name('index');

        Route::get('/{type}', function (string $type) {
            $user = request()->user();
            $content = EmailContent::load();
            $branding = EmailContent::branding();

            return match ($type) {
                'reset-password' => view('emails.auth.reset-password', [
                    ...$branding,
                    'content' => $content['reset_password'],
                    'user' => $user,
                    'resetUrl' => url(route('password.reset', [
                        'token' => 'preview-token',
                        'email' => $user->email,
                    ], false)),
                ]),
                'verify-email' => view('emails.auth.verify-email', [
                    ...$branding,
                    'content' => $content['verify_email'],
                    'user' => $user,
                    'verificationUrl' => url('/email/verify/preview-link'),
                ]),
                'welcome' => view('emails.auth.welcome', [
                    ...$branding,
                    'content' => $content['welcome'],
                    'user' => $user,
                    'dashboardUrl' => url('/dashboard'),
                ]),
                default => abort(404),
            };
        })->name('show');
    });

Route::middleware(['auth', 'verified', 'employer', 'portal.capability:employers'])->prefix('business')->name('business.')->group(function (): void {
    Route::get('/', [EmployerWorkspaceController::class, 'dashboard'])->name('dashboard');
    Route::get('/company', [EmployerWorkspaceController::class, 'company'])->name('company');
    Route::post('/company', [EmployerWorkspaceController::class, 'updateCompany'])->name('company.update');
    Route::get('/jobs', [EmployerWorkspaceController::class, 'jobs'])->name('jobs');
    Route::post('/jobs', [EmployerWorkspaceController::class, 'storeJob'])->name('jobs.store');
    Route::put('/jobs/{job}', [EmployerWorkspaceController::class, 'updateJob'])->name('jobs.update');
    Route::get('/applicants', [EmployerWorkspaceController::class, 'applicants'])->name('applicants');
    Route::put('/applicants/{application}', [EmployerWorkspaceController::class, 'updateApplication'])->name('applicants.update');
    Route::post('/applicants/{application}/message', [EmployerWorkspaceController::class, 'messageApplicant'])->name('applicants.message');
    Route::get('/candidates', [EmployerWorkspaceController::class, 'candidates'])->name('candidates');
    Route::post('/candidates/{candidate}/invite', [EmployerWorkspaceController::class, 'inviteCandidate'])->name('candidates.invite');
    Route::post('/candidates/{candidate}/cv-access', [EmployerWorkspaceController::class, 'accessCandidateCv'])->name('candidates.cv-access');
    Route::post('/candidates/{candidate}/contact-access', [EmployerWorkspaceController::class, 'requestCandidateContact'])->name('candidates.contact-access');
    Route::get('/billing', [EmployerWorkspaceController::class, 'billing'])->name('billing');
    Route::post('/billing', [EmployerWorkspaceController::class, 'updateBilling'])->name('billing.update');
    Route::get('/advertise', static fn () => abort(404))->name('promotion');
    Route::post('/advertise', static fn () => abort(404))->name('promotion.store');
    Route::get('/paid-services', static fn () => abort(404))->name('services');
    Route::post('/paid-services', static fn () => abort(404))->name('services.store');
});

Route::middleware('portal.capability:content')->group(function (): void {
    Route::get('/policies/{slug}', function (string $slug) {
        $portal = PortalData::load();
        abort_unless(array_key_exists($slug, $portal['policies']), 404);

        return view('portal.policy', ['portal' => $portal, 'slug' => $slug, 'title' => $portal['policies'][$slug]]);
    })->name('policy');

    Route::get('/{slug}', function (string $slug) {
        $portal = PortalData::load();
        abort_unless(array_key_exists($slug, $portal['seo_pages']), 404);

        return view('portal.seo-landing', [
            'portal' => $portal,
            'slug' => $slug,
            'page' => $portal['seo_pages'][$slug],
        ]);
    })->name('seo.landing');
});
