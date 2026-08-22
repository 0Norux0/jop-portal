@php($pageContent = \App\Support\PageContent::get('jobs'))
<x-layouts.app :title="$pageContent['title']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
            <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $pageContent['title'] }}</h1>
            <p class="mt-3 max-w-3xl text-slate-600">{{ $pageContent['description'] }}</p>

            @if (session('status'))
                <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
            @endif

            <form method="GET" class="mt-6 rounded-lg bg-white p-5 shadow-[0_18px_45px_rgba(15,23,42,0.10)]">
                <div class="grid gap-3 md:grid-cols-[1fr_auto]">
                    <input name="q" value="{{ $filters['q'] ?? '' }}" class="rounded border-slate-300 px-4 py-3 text-sm" placeholder="Search jobs, companies, skills, or locations">
                    <button class="rounded bg-[#2a7190] px-5 py-3 text-sm font-bold text-white">Search</button>
                </div>

                <details class="mt-4" @if(collect($filters ?? [])->except('q')->filter()->isNotEmpty()) open @endif>
                    <summary class="cursor-pointer text-sm font-bold text-[#2a7190]">Filter</summary>
                    <div class="mt-4 grid gap-3 md:grid-cols-3">
                        <select name="country" class="rounded border-slate-300 px-4 py-3 text-sm">
                            <option value="">All countries</option>
                            @foreach ($portal['countries'] as $country)
                                <option value="{{ $country }}" @selected(($filters['country'] ?? '') === $country)>{{ $country }}</option>
                            @endforeach
                        </select>

                        <select name="category" class="rounded border-slate-300 px-4 py-3 text-sm">
                            <option value="">All categories</option>
                            @foreach ($portal['categories'] as $category)
                                <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>
                            @endforeach
                        </select>

                        <select name="work_mode" class="rounded border-slate-300 px-4 py-3 text-sm">
                            <option value="">Any work mode</option>
                            @foreach (['on_site' => 'On-site', 'remote' => 'Remote', 'hybrid' => 'Hybrid'] as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['work_mode'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>

                        <select name="employment_type" class="rounded border-slate-300 px-4 py-3 text-sm">
                            <option value="">Any employment type</option>
                            @foreach (['full_time' => 'Full-time', 'part_time' => 'Part-time', 'contract' => 'Contract', 'freelance' => 'Freelance', 'internship' => 'Internship'] as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['employment_type'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>

                        <label class="flex items-center gap-2 rounded border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700">
                            <input type="checkbox" name="visa" value="1" @checked(($filters['visa'] ?? '') === '1')>
                            Visa sponsorship
                        </label>

                        <label class="flex items-center gap-2 rounded border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700">
                            <input type="checkbox" name="urgent" value="1" @checked(($filters['urgent'] ?? '') === '1')>
                            Urgent hiring
                        </label>
                    </div>
                </details>
            </form>
        </div>
    </section>

    <section class="mx-auto grid max-w-7xl gap-5 px-4 py-10 sm:px-6 lg:px-8">
        @forelse ($portal['jobs'] as $job)
            <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-xl font-bold"><a href="/jobs/{{ $job['slug'] }}">{{ $job['title'] }}</a></h2>
                            @if ($job['urgent'])
                                <span class="rounded bg-red-50 px-2 py-1 text-xs font-semibold text-red-700">Urgent</span>
                            @endif
                        </div>
                        <p class="mt-2 text-slate-600">{{ $job['company'] }} · {{ $job['city'] }}, {{ $job['country'] }} · {{ $job['mode'] }}</p>
                        <p class="mt-2 font-medium">{{ $job['salary'] }} · {{ $job['type'] }}</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($job['badges'] as $badge)
                                <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-xs font-semibold text-[#2a7190]">{{ $badge }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="/jobs/{{ $job['slug'] }}#apply" class="rounded bg-[#2a7190] px-4 py-2 text-sm font-semibold text-white">Apply</a>
                        <a href="/login" class="rounded border border-slate-300 px-4 py-2 text-sm font-semibold">Save</a>
                        <a href="mailto:?subject={{ rawurlencode($job['title'].' at '.$job['company']) }}&body={{ rawurlencode(url('/jobs/'.$job['slug'])) }}" class="rounded border border-slate-300 px-4 py-2 text-sm font-semibold">Share</a>
                    </div>
                </div>
            </article>
        @empty
            <p class="rounded-lg border border-slate-200 bg-white p-6 text-sm text-slate-600 shadow-sm">No jobs match these filters yet.</p>
        @endforelse
    </section>
</x-layouts.app>