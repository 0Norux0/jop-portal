@php
    $site = \App\Support\SiteContent::load();
    $primary = $site['brand']['primary_color'];
    $secondary = $site['brand']['secondary_color'];
@endphp
<x-layouts.app :title="$site['brand']['name']">
    <section class="relative overflow-hidden" style="background: {{ $secondary }};">
        <div class="mx-auto grid min-h-[720px] max-w-7xl lg:grid-cols-[58%_42%]">
            <div class="flex flex-col justify-center px-6 pb-12 pt-6 sm:px-10 lg:px-14">
                <p class="mb-5 text-sm font-semibold uppercase tracking-[0.24em]" style="color: {{ $primary }};">
                    {{ $site['home']['eyebrow'] }}
                </p>
                <h1 class="max-w-[620px] text-[58px] font-extrabold leading-[0.98] tracking-normal sm:text-[72px]" style="color: {{ $primary }};">
                    {!! nl2br(e($site['home']['headline'])) !!}
                </h1>
                <p class="mt-8 max-w-[620px] text-base leading-7 text-slate-700">
                    {{ $site['home']['description'] }}
                </p>

                <form action="/jobs" class="mt-16 grid max-w-[650px] gap-4 rounded-lg bg-white p-5 shadow-[0_18px_45px_rgba(15,23,42,0.10)] sm:grid-cols-[1fr_1fr_auto]">
                    <label class="flex items-center gap-3 rounded bg-slate-50 px-4 py-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full text-white" style="background: {{ $primary }};">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="m20 20-4.2-4.2m1.2-5.3a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <input name="q" class="w-full border-0 bg-transparent p-0 text-sm focus:shadow-none" placeholder="{{ $site['home']['keyword_placeholder'] }}">
                    </label>
                    <label class="flex items-center gap-3 rounded bg-slate-50 px-4 py-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full text-white" style="background: {{ $primary }};">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 21s7-5.1 7-11a7 7 0 1 0-14 0c0 5.9 7 11 7 11Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M12 10.5h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <select name="location" class="w-full border-0 bg-transparent p-0 text-sm focus:shadow-none">
                            <option>{{ $site['home']['location_placeholder'] }}</option>
                            @foreach ($portal['countries'] as $country)
                                <option>{{ $country }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button class="rounded px-8 py-3 text-sm font-semibold text-white" style="background: {{ $primary }};">
                        {{ $site['home']['search_button_label'] }}
                    </button>
                    <select name="category" class="rounded bg-slate-50 px-4 py-3 text-sm text-slate-700 sm:col-span-1">
                        <option>Category</option>
                        @foreach ($portal['categories'] as $category)
                            <option>{{ $category }}</option>
                        @endforeach
                    </select>
                    <select name="salary" class="rounded bg-slate-50 px-4 py-3 text-sm text-slate-700 sm:col-span-1">
                        <option>Salary range</option>
                        <option>Disclosed salary</option>
                        <option>Negotiable</option>
                        <option>Visa support</option>
                    </select>
                    <div class="flex flex-wrap gap-2 sm:col-span-1">
                        <a href="/employers" class="rounded border px-4 py-3 text-sm font-semibold" style="border-color: {{ $primary }}; color: {{ $primary }};">Hire Talent</a>
                        <a href="/register" class="rounded border px-4 py-3 text-sm font-semibold" style="border-color: {{ $primary }}; color: {{ $primary }};">Upload CV</a>
                        <a href="/employers" class="rounded border px-4 py-3 text-sm font-semibold" style="border-color: {{ $primary }}; color: {{ $primary }};">Post a Job</a>
                    </div>
                </form>
            </div>

            <div class="relative min-h-[580px] overflow-hidden lg:rounded-bl-[90px]" style="background: {{ $primary }};">
                <img src="{{ \App\Support\SiteContent::assetUrl($site['home']['hero_image_path'], 'images/global-career-hero.webp') }}" alt="Global talent hiring platform" class="absolute inset-0 h-full w-full object-cover opacity-90 mix-blend-screen" loading="eager" fetchpriority="high">
                <div class="absolute inset-0" style="background: color-mix(in srgb, {{ $primary }} 35%, transparent);"></div>
                <div class="absolute left-10 top-28 hidden h-20 w-20 items-center justify-center rounded-full bg-white/95 shadow-xl sm:flex" style="color: {{ $primary }};">
                    <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 3 5 6v5c0 4.5 2.9 8.4 7 10 4.1-1.6 7-5.5 7-10V6l-7-3Z" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 7v10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="absolute right-10 top-44 hidden h-20 w-20 items-center justify-center rounded-full bg-white/95 shadow-xl sm:flex" style="color: {{ $primary }};">
                    <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="m8 12 3 3 5-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 3.5 14.4 6l3.4-.2-.2 3.4L20 12l-2.4 2.8.2 3.4-3.4-.2L12 20.5 9.6 18l-3.4.2.2-3.4L4 12l2.4-2.8-.2-3.4 3.4.2L12 3.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="absolute bottom-24 left-14 hidden h-20 w-20 items-center justify-center rounded-full bg-white/95 shadow-xl sm:flex" style="color: {{ $primary }};">
                    <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M7 4h10v16H7z" stroke="currentColor" stroke-width="2"/>
                        <path d="M9 8h6M9 12h6M9 16h3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    @if ($site['home_sections']['stats']['enabled'])
    <section class="bg-white">
        <div class="mx-auto grid max-w-7xl gap-5 px-6 py-10 sm:grid-cols-2 lg:grid-cols-4 lg:px-8">
            @foreach ($portal['stats'] as $stat)
                <div class="rounded-lg border border-slate-200 p-5">
                    <p class="text-3xl font-extrabold" style="color: {{ $primary }};">{{ $stat['value'] }}</p>
                    <p class="mt-1 text-sm text-slate-600">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    @if ($site['home_sections']['jobs']['enabled'])
    <section style="background: {{ $secondary }};">
        <div class="mx-auto max-w-7xl px-6 py-14 lg:px-8">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="font-semibold" style="color: {{ $primary }};">{{ $site['home']['featured_jobs_subheading'] }}</p>
                    <h2 class="mt-2 text-3xl font-extrabold text-slate-950">{{ $site['home']['featured_jobs_heading'] }}</h2>
                </div>
                <a href="/jobs" class="font-semibold" style="color: {{ $primary }};">View all jobs</a>
            </div>
            <div class="mt-7 grid gap-5 lg:grid-cols-4">
                @foreach ($portal['jobs'] as $job)
                    <a href="/jobs/{{ $job['slug'] }}" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm hover:border-[#2a7190]">
                        <div class="flex items-center justify-between gap-3">
                            <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-xs font-bold" style="color: {{ $primary }};">{{ $job['mode'] }}</span>
                            @if ($job['urgent'])
                                <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700">Urgent</span>
                            @endif
                        </div>
                        <h3 class="mt-4 text-lg font-bold">{{ $job['title'] }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $job['company'] }}</p>
                        <p class="mt-3 text-sm font-semibold text-slate-800">{{ $job['city'] }}, {{ $job['country'] }}</p>
                        <p class="mt-2 text-sm text-slate-600">{{ $job['salary'] }}</p>
                        <span class="mt-4 inline-flex text-sm font-bold" style="color: {{ $primary }};">View details</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ($site['home_sections']['employers']['enabled'])
    <section class="bg-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-6 py-14 lg:grid-cols-[0.85fr_1.15fr] lg:px-8">
            <div>
                <p class="font-semibold" style="color: {{ $primary }};">Featured employers</p>
                <h2 class="mt-2 text-3xl font-extrabold">{{ $site['home']['employers_heading'] }}</h2>
                <p class="mt-4 leading-7 text-slate-600">{{ $site['home']['employers_description'] }}</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach (['Northbridge Care Group' => 'United Kingdom · Care homes', 'MapleCloud Labs' => 'Canada · Remote technology', 'Pearl Vista Hotels' => 'UAE · Hospitality', 'GulfSecure Systems' => 'Kuwait · Cybersecurity'] as $company => $meta)
                    <a href="/employers" class="rounded-lg border border-slate-200 bg-[#f7f7f7] p-5 transition hover:border-[#2a7190] hover:bg-white">
                        <div class="flex h-12 w-12 items-center justify-center rounded text-lg font-bold text-white" style="background: {{ $primary }};">{{ substr($company, 0, 1) }}</div>
                        <h3 class="mt-4 font-bold">{{ $company }}</h3>
                        <p class="mt-1 text-sm text-slate-600">{{ $meta }}</p>
                        <span class="mt-3 inline-flex rounded-full bg-[#e9f3f7] px-3 py-1 text-xs font-bold text-[#2a7190]">Verified employer</span>
                        <span class="mt-4 block text-sm font-bold" style="color: {{ $primary }};">View employer tools</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ($site['home_sections']['candidate_pitch']['enabled'])
    <section class="bg-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-6 py-14 lg:grid-cols-2 lg:px-8">
            <div>
                <p class="font-semibold" style="color: {{ $primary }};">For candidates</p>
                <h2 class="mt-2 text-3xl font-extrabold">{{ $site['home']['candidates_heading'] }}</h2>
                <p class="mt-4 leading-7 text-slate-600">{{ $site['home']['candidates_description'] }}</p>
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach (array_slice($portal['badges'], 0, 8) as $badge)
                        <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-sm font-semibold text-[#2a7190]">{{ $badge }}</span>
                    @endforeach
                </div>
            </div>
            <div>
                <p class="font-semibold" style="color: {{ $primary }};">For employers</p>
                <h2 class="mt-2 text-3xl font-extrabold">{{ $site['home']['employer_tools_heading'] }}</h2>
                <p class="mt-4 leading-7 text-slate-600">{{ $site['home']['employer_tools_description'] }}</p>
                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    @foreach (['Verified employer badges', 'Candidate search credits', 'Urgent hiring packages', 'Recruitment agency tools'] as $item)
                        <div class="rounded-lg border border-slate-200 p-4 font-semibold">{{ $item }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($site['home_sections']['verified_candidates']['enabled'])
    <section style="background: {{ $secondary }};">
        <div class="mx-auto max-w-7xl px-6 py-14 lg:px-8">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="font-semibold" style="color: {{ $primary }};">{{ $site['home']['verified_candidates_subheading'] }}</p>
                    <h2 class="mt-2 text-3xl font-extrabold">{{ $site['home']['verified_candidates_heading'] }}</h2>
                </div>
                <a href="/job-seekers" class="font-semibold" style="color: {{ $primary }};">Build your profile</a>
            </div>
            <div class="mt-7 grid gap-5 lg:grid-cols-3">
                @foreach ($portal['candidates'] as $candidate)
                    <a href="/candidate-profile" class="rounded-lg border border-slate-200 bg-white p-5 transition hover:border-[#2a7190]">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#2a7190] text-lg font-bold text-white">{{ collect(explode(' ', $candidate['name']))->map(fn ($part) => substr($part, 0, 1))->join('') }}</div>
                            <div>
                                <h3 class="font-bold">{{ $candidate['name'] }}</h3>
                                <p class="text-sm text-slate-600">{{ $candidate['country'] }}</p>
                            </div>
                        </div>
                        <p class="mt-4 font-semibold">{{ $candidate['headline'] }}</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($candidate['badges'] as $badge)
                                <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-xs font-bold text-[#2a7190]">{{ $badge }}</span>
                            @endforeach
                        </div>
                        <p class="mt-4 text-sm text-slate-600">{{ implode(' · ', $candidate['skills']) }}</p>
                        <span class="mt-4 block text-sm font-bold" style="color: {{ $primary }};">View profile</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ($site['home_sections']['categories']['enabled'])
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-6 py-14 lg:px-8">
            <p class="font-semibold" style="color: {{ $primary }};">{{ $site['home']['categories_subheading'] }}</p>
            <h2 class="mt-2 text-3xl font-extrabold">{{ $site['home']['categories_heading'] }}</h2>
            <div class="mt-7 flex flex-wrap gap-3">
                @foreach ($portal['categories'] as $category)
                    <a href="/jobs?category={{ urlencode($category) }}" class="rounded-full border border-slate-300 bg-[#f7f7f7] px-4 py-2 text-sm font-semibold hover:border-[#2a7190] hover:text-[#2a7190]">{{ $category }}</a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ($site['home_sections']['stories']['enabled'])
    <section style="background: {{ $secondary }};">
        <div class="mx-auto grid max-w-7xl gap-8 px-6 py-14 lg:grid-cols-3 lg:px-8">
            <div>
                <p class="font-semibold" style="color: {{ $primary }};">{{ $site['home']['stories_subheading'] }}</p>
                <h2 class="mt-2 text-3xl font-extrabold">{{ $site['home']['stories_heading'] }}</h2>
                <p class="mt-4 leading-7 text-slate-600">{{ $site['home']['stories_description'] }}</p>
            </div>
            <div class="grid gap-5 lg:col-span-2 sm:grid-cols-3">
                @foreach (['Caregiver relocated with verified training documents', 'Remote developer hired through portfolio review', 'Fresh graduate shortlisted using skill badges'] as $story)
                    <article class="rounded-lg border border-slate-200 bg-white p-5">
                        <p class="text-sm font-semibold leading-6">{{ $story }}</p>
                        <p class="mt-4 text-xs font-bold uppercase tracking-wide text-[#2a7190]">Story placeholder</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ($site['home_sections']['how_it_works']['enabled'])
    <section class="bg-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-6 py-14 lg:grid-cols-2 lg:px-8">
            <div class="rounded-lg border border-slate-200 p-6">
                <p class="font-semibold text-[#2a7190]">How it works for job seekers</p>
                <ol class="mt-5 grid gap-4 text-sm leading-6 text-slate-700">
                    <li><strong>1. Create a free profile</strong><br>Register globally and add your career goals, country, skills, and preferences.</li>
                    <li><strong>2. Add CV, video, and portfolio</strong><br>Show employers verified certificates, projects, and your professional introduction.</li>
                    <li><strong>3. Apply and track progress</strong><br>Use saved jobs, applications, alerts, and interview status as modules are enabled.</li>
                </ol>
            </div>
            <div class="rounded-lg border border-slate-200 p-6">
                <p class="font-semibold text-[#2a7190]">How it works for employers</p>
                <ol class="mt-5 grid gap-4 text-sm leading-6 text-slate-700">
                    <li><strong>1. Create and verify company account</strong><br>Build trust with official company and recruiter verification workflows.</li>
                    <li><strong>2. Post clear international jobs</strong><br>Publish salary, location, visa, accommodation, transport, and work-permit details.</li>
                    <li><strong>3. Shortlist stronger candidates</strong><br>Review CVs, skills, portfolios, video profiles, and verification badges.</li>
                </ol>
            </div>
        </div>
    </section>
    @endif
</x-layouts.app>
