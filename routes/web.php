<?php

declare(strict_types=1);

use App\Support\PortalData;
use App\Support\PortalReports;
use App\Support\CareerCoach;
use App\Support\InstitutionMetrics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', static fn () => view('welcome', ['portal' => PortalData::load()]))->name('home');

Route::middleware('portal.capability:candidates')->group(function (): void {
    Route::get('/job-seekers', static fn () => view('portal.job-seekers', ['portal' => PortalData::load()]))->name('job-seekers');
    Route::get('/candidate-profile', static fn () => view('portal.candidate-profile', ['portal' => PortalData::load()]))->name('candidate-profile');
    Route::get('/cv-builder', static fn () => view('portal.cv-builder', ['portal' => PortalData::load()]))->name('cv-builder');
    Route::get('/video-profile', static fn () => view('portal.video-profile', ['portal' => PortalData::load()]))->name('video-profile');
    Route::get('/portfolio', static fn () => view('portal.portfolio', ['portal' => PortalData::load()]))->name('portfolio');
});

Route::middleware('portal.capability:employers')->group(function (): void {
    Route::get('/employers', static fn () => view('portal.employers', ['portal' => PortalData::load()]))->name('employers');
    Route::get('/employer-register', static fn () => view('portal.employer-register', ['portal' => PortalData::load()]))->name('employer-register');
    Route::get('/employer-dashboard', static fn () => view('portal.employer-dashboard', ['portal' => PortalData::load()]))->name('employer-dashboard');
    Route::get('/candidate-search', static fn () => view('portal.candidate-search', ['portal' => PortalData::load()]))->name('candidate-search');
    Route::get('/packages', static fn () => view('portal.packages', ['portal' => PortalData::load()]))->name('packages');
});

Route::middleware('portal.capability:content')->group(function (): void {
    Route::get('/career-ecosystem', static fn () => view('portal.ecosystem', ['portal' => PortalData::load()]))->name('ecosystem');
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
    Route::get('/jobs', static fn () => view('portal.jobs.index', ['portal' => PortalData::load()]))->name('jobs.index');
    Route::get('/jobs/{slug}', function (string $slug) {
        $portal = PortalData::load();
        $job = collect($portal['jobs'])->firstWhere('slug', $slug);

        abort_unless($job, 404);

        return view('portal.jobs.show', ['portal' => $portal, 'job' => $job]);
    })->name('jobs.show');
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

Route::middleware(['auth', 'verified', 'portal.capability:candidates'])->group(function (): void {
    Route::get('/dashboard', static fn () => view('dashboard', ['portal' => PortalData::load()]))->name('dashboard');
});
