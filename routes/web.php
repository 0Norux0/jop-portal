<?php

declare(strict_types=1);

use App\Support\PortalData;
use App\Support\PortalReports;
use App\Support\CareerCoach;
use App\Support\InstitutionMetrics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$portalView = static fn (string $view): \Closure => static fn () => view($view, ['portal' => PortalData::load()]);

Route::get('/', static fn () => view('welcome', ['portal' => PortalData::load()]))->name('home');

Route::middleware('portal.capability:candidates')->group(function () use ($portalView): void {
    Route::get('/job-seekers', $portalView('portal.job-seekers'))->name('job-seekers');
    Route::get('/candidate-profile', $portalView('portal.candidate-profile'))->name('candidate-profile');
    Route::get('/cv-builder', $portalView('portal.cv-builder'))->name('cv-builder');
    Route::get('/video-profile', $portalView('portal.video-profile'))->name('video-profile');
    Route::get('/portfolio', $portalView('portal.portfolio'))->name('portfolio');
});

Route::middleware('portal.capability:employers')->group(function () use ($portalView): void {
    Route::get('/employers', $portalView('portal.employers'))->name('employers');
    Route::get('/employer-register', $portalView('portal.employer-register'))->name('employer-register');
    Route::get('/employer-dashboard', $portalView('portal.employer-dashboard'))->name('employer-dashboard');
    Route::get('/candidate-search', $portalView('portal.candidate-search'))->name('candidate-search');
    Route::get('/packages', $portalView('portal.packages'))->name('packages');
});

Route::middleware('portal.capability:content')->group(function () use ($portalView): void {
    Route::get('/career-ecosystem', $portalView('portal.ecosystem'))->name('ecosystem');
    Route::get('/career-resources', $portalView('portal.blog'))->name('blog');
    Route::get('/career-coach', function (Request $request) {
        $input = $request->only(['target_role', 'skills', 'has_cv', 'has_portfolio', 'has_video']);

        return view('portal.career-coach', [
            'portal' => PortalData::load(),
            'input' => $input,
            'advice' => filled($input['target_role'] ?? '') ? CareerCoach::advise($input) : null,
        ]);
    })->name('career-coach');
});

Route::middleware('portal.capability:international')->group(function () use ($portalView): void {
    Route::get('/international-support', $portalView('portal.international-support'))->name('international-support');
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

Route::middleware('portal.capability:trust_safety')->group(function () use ($portalView): void {
    Route::get('/trust-safety', $portalView('portal.trust-safety'))->name('trust-safety');
    Route::get('/platform-admin', $portalView('portal.platform-admin'))->name('platform-admin');
    Route::get('/candidate-verification', $portalView('portal.candidate-verification'))->name('candidate-verification');
    Route::get('/employer-verification', $portalView('portal.employer-verification'))->name('employer-verification');
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

Route::middleware(['auth', 'verified', 'portal.capability:candidates'])->group(function () use ($portalView): void {
    Route::get('/dashboard', $portalView('dashboard'))->name('dashboard');
});
