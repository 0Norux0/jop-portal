@php
    $pageContent = \App\Support\PageContent::get('platform-admin');
    $reports = \App\Support\PortalReports::all();
@endphp
<x-layouts.app :title="$pageContent['title']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
            <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $pageContent['title'] }}</h1>
            <p class="mt-4 max-w-3xl leading-7 text-slate-700">{{ $pageContent['description'] }}</p>
            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    'User management',
                    'Candidate management',
                    'Employer management',
                    'Job approval',
                    'Candidate verification',
                    'Employer verification',
                    'Payments',
                    'Employer services',
                    'Credits',
                    'Reports',
                    'Content management',
                    'SEO pages',
                    'Blog articles',
                    'Complaints/reports',
                    'Messages monitoring',
                    'Country/city/category management',
                    'Skills database',
                    'Course database',
                    'Certificates database',
                    'Success stories',
                    'Newsletter management',
                ] as $module)
                    <div class="rounded-lg bg-white p-5 shadow-sm">
                        <div class="flex h-11 w-11 items-center justify-center rounded bg-[#2a7190] text-sm font-bold text-white">{{ substr($module, 0, 1) }}</div>
                        <h2 class="mt-4 font-bold">{{ $module }}</h2>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 rounded-lg border border-slate-200 bg-white p-6">
                <p class="font-semibold">Protected operations</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">These modules are represented here for product design. Operational access is restricted to authorized staff through protected internal controls.</p>
            </div>
            <div class="mt-8 rounded-lg border border-slate-200 bg-white p-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="font-semibold">Admin review queue</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Newest safety reports submitted through the portal. Full handling belongs in the protected admin workflow.</p>
                    </div>
                    <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-sm font-bold text-[#2a7190]">{{ count($reports) }} pending</span>
                </div>
                <div class="mt-5 grid gap-3">
                    @forelse (array_slice($reports, 0, 5) as $report)
                        <div class="rounded border border-slate-200 bg-slate-50 p-4 text-sm">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="font-bold">{{ ucfirst($report['type'] ?? 'report') }}: {{ $report['subject'] ?? 'Safety report' }}</p>
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-600">{{ $report['status'] ?? 'pending_review' }}</span>
                            </div>
                            <p class="mt-2 text-slate-600">{{ $report['reason'] ?? 'Safety concern' }}</p>
                            @if (! empty($report['flags']))
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach ($report['flags'] as $flag)
                                        <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">{{ $flag }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="rounded border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">No safety reports submitted yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
