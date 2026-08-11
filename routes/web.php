<?php

declare(strict_types=1);

use App\Support\PortalData;
use App\Support\PortalJobPresenter;
use App\Support\PortalReports;
use App\Support\CareerCoach;
use App\Support\InstitutionMetrics;
use App\Http\Controllers\CandidateWorkspaceController;
use App\Http\Controllers\EmployerWorkspaceController;
use App\Domain\Identity\Enums\Role;
use App\Domain\Portal\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', static fn () => view('welcome', ['portal' => PortalData::load()]))->name('home');

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
    Route::get('/employer-dashboard', static fn () => view('portal.employer-dashboard', ['portal' => PortalData::load()]))->name('employer-dashboard');
    Route::get('/candidate-search', static fn () => view('portal.candidate-search', ['portal' => PortalData::load()]))->name('candidate-search');
    Route::get('/packages', static fn () => view('portal.packages', ['portal' => PortalData::load()]))->name('packages');
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
    Route::get('/trust-safety', static fn () => view('portal.trust-safety', ['portal' => PortalData::load()]))->name('trust-safety');
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

        $report = PortalReports::create($validated);

        return redirect('/trust-safety')->with('status', 'Report submitted for admin review. Reference: '.$report['id']);
    })->name('report.store');
});

Route::middleware('portal.capability:jobs')->group(function (): void {
    Route::get('/jobs', static function () {
        $portal = PortalData::load();
        $portal['jobs'] = PortalJobPresenter::publishedJobs();

        return view('portal.jobs.index', ['portal' => $portal]);
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
    Route::get('/admin-center', [EmployerWorkspaceController::class, 'billing'])->name('billing');
    Route::post('/admin-center', [EmployerWorkspaceController::class, 'updateBilling'])->name('billing.update');
    Route::get('/advertise', [EmployerWorkspaceController::class, 'promotion'])->name('promotion');
    Route::post('/advertise', [EmployerWorkspaceController::class, 'storePromotion'])->name('promotion.store');
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
