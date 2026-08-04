@php($pageContent = \App\Support\PageContent::get('employer-dashboard'))
<x-layouts.app :title="$pageContent['title']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
            <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $pageContent['title'] }}</h1>
            <p class="mt-4 max-w-3xl leading-7 text-slate-700">{{ $pageContent['description'] }}</p>
            <div class="mt-8 grid gap-5 lg:grid-cols-4">
                @foreach (['Active jobs' => '12', 'Applicants' => '386', 'Shortlisted' => '48', 'Profile views' => '1.8k'] as $label => $value)
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <p class="text-3xl font-extrabold text-[#2a7190]">{{ $value }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 grid gap-5 lg:grid-cols-3">
                @foreach ([
                    'Hiring actions' => ['Post jobs', 'Manage jobs', 'View applicants', 'Shortlist candidates', 'Invite candidates to apply', 'Schedule interviews'],
                    'Candidate review' => ['Search candidates', 'Download CVs', 'Watch video profiles', 'View portfolios', 'Message candidates'],
                    'Commercial tools' => ['Buy job packages', 'Buy CV search credits', 'View analytics'],
                ] as $title => $items)
                    <section class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold">{{ $title }}</h2>
                        <div class="mt-4 grid gap-3">
                            @foreach ($items as $item)
                                <div class="rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold">{{ $item }}</div>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.app>
