@php($pageContent = \App\Support\PageContent::get('jobs'))
<x-layouts.app :title="$pageContent['title']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
            <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $pageContent['title'] }}</h1>
            <p class="mt-3 max-w-3xl text-slate-600">{{ $pageContent['description'] }}</p>
            <div class="mt-6 grid gap-3 rounded-lg bg-white p-5 shadow-[0_18px_45px_rgba(15,23,42,0.10)] md:grid-cols-4">
                @foreach (['Country', 'City', 'Remote / hybrid / on-site', 'Job category', 'Industry', 'Salary range', 'Currency', 'Experience level', 'Education level', 'Employment type', 'Visa sponsorship available', 'Date posted'] as $filter)
                    <select class="rounded bg-slate-50 text-sm"><option>{{ $filter }}</option></select>
                @endforeach
            </div>
            <div class="mt-5">
                <p class="text-sm font-bold text-slate-700">Employment type</p>
                <div class="mt-3 flex flex-wrap gap-2 text-xs">
                    @foreach (['Full-time', 'Part-time', 'Contract', 'Freelance', 'Internship', 'Temporary', 'Apprenticeship', 'Fresh graduate', 'Skilled worker'] as $chip)
                        <label class="rounded-full border border-slate-200 bg-white px-3 py-2 font-semibold text-slate-700">
                            <input type="checkbox" class="mr-1 align-middle"> {{ $chip }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="mt-5">
                <p class="text-sm font-bold text-slate-700">International support and trust</p>
                <div class="mt-3 flex flex-wrap gap-2 text-xs">
                    @foreach (['Relocation support', 'Work permit support', 'Accommodation provided', 'Transportation provided', 'Urgent hiring', 'Verified employer', 'Salary disclosed', 'Language requirement', 'Nationality requirement where legal', 'Gender requirement where legal', 'Disability-friendly employer', 'Overseas applicants accepted'] as $chip)
                        <label class="rounded-full border border-slate-200 bg-white px-3 py-2 font-semibold text-slate-700">
                            <input type="checkbox" class="mr-1 align-middle"> {{ $chip }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto grid max-w-7xl gap-5 px-4 py-10 sm:px-6 lg:px-8">
        @foreach ($portal['jobs'] as $job)
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
        @endforeach
    </section>
</x-layouts.app>
