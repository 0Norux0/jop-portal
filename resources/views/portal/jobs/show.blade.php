<x-layouts.app :title="$job['title']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <a href="/jobs" class="text-sm font-semibold text-[#2a7190]">Back to jobs</a>
            <div class="mt-5 grid gap-8 lg:grid-cols-[1fr_360px]">
                <article>
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="flex h-16 w-16 items-center justify-center rounded bg-[#2a7190] text-xl font-bold text-white">{{ substr($job['company'], 0, 1) }}</div>
                        <div>
                            <h1 class="text-3xl font-bold">{{ $job['title'] }}</h1>
                            <p class="mt-2 text-slate-600">{{ $job['company'] }} · {{ $job['city'] }}, {{ $job['country'] }} · {{ $job['mode'] }}</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach ($job['badges'] as $badge)
                                    <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-xs font-semibold text-[#2a7190]">{{ $badge }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 grid gap-3 text-sm sm:grid-cols-3">
                        <div class="rounded bg-slate-50 p-4"><p class="text-slate-500">Location</p><p class="mt-1 font-bold">{{ $job['city'] }}, {{ $job['country'] }}</p></div>
                        <div class="rounded bg-slate-50 p-4"><p class="text-slate-500">Work mode</p><p class="mt-1 font-bold">{{ $job['mode'] }}</p></div>
                        <div class="rounded bg-slate-50 p-4"><p class="text-slate-500">Salary</p><p class="mt-1 font-bold">{{ $job['salary'] }}</p></div>
                    </div>
                    </div>
                    <div class="mt-8 grid gap-6">
                        <section class="rounded-lg border border-slate-200 bg-white p-5">
                            <h2 class="font-bold">Job description</h2>
                            <p class="mt-3 text-slate-700">{{ $job['description'] }}</p>
                        </section>
                        @foreach (['Responsibilities' => 'responsibilities', 'Required skills' => 'skills', 'Requirements' => 'requirements', 'Benefits' => 'benefits'] as $label => $key)
                            <section class="rounded-lg border border-slate-200 bg-white p-5">
                                <h2 class="font-bold">{{ $label }}</h2>
                                <ul class="mt-3 grid gap-2 text-slate-700">
                                    @foreach ($job[$key] as $item)
                                        <li>• {{ $item }}</li>
                                    @endforeach
                                </ul>
                            </section>
                        @endforeach
                        <section class="rounded-lg border border-slate-200 bg-white p-5">
                            <h2 class="font-bold">Language, visa, and support details</h2>
                            <dl class="mt-3 grid gap-3 text-sm text-slate-700 sm:grid-cols-2">
                                <div><dt class="font-semibold">Language requirements</dt><dd>English required; additional languages are an advantage depending on country and employer.</dd></div>
                                <div><dt class="font-semibold">Visa / work permit</dt><dd>{{ in_array('Visa sponsorship', $job['badges'], true) ? 'Visa sponsorship information is available from this employer.' : 'Work authorization requirements depend on candidate location and employer policy.' }}</dd></div>
                                <div><dt class="font-semibold">Accommodation</dt><dd>{{ str_contains(implode(' ', $job['badges']), 'Accommodation') ? 'Accommodation support is listed for this role.' : 'Accommodation details are provided by the employer where applicable.' }}</dd></div>
                                <div><dt class="font-semibold">Transportation</dt><dd>{{ str_contains(implode(' ', $job['badges']), 'Transportation') ? 'Transportation is listed for this role.' : 'Transportation details are provided by the employer where applicable.' }}</dd></div>
                            </dl>
                        </section>
                        <section class="rounded-lg border border-slate-200 bg-white p-5">
                            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                                <div>
                                    <h2 class="font-bold">Company profile</h2>
                                    <p class="mt-2 text-sm text-slate-600">{{ $job['company'] }} is shown as a verified employer preview. Full company profile pages will connect here.</p>
                                </div>
                                <a href="/employers" class="rounded border border-[#2a7190] px-4 py-2 text-sm font-semibold text-[#2a7190]">View company profile</a>
                            </div>
                        </section>
                        <section class="rounded-lg border border-slate-200 bg-white p-5">
                            <h2 class="font-bold">Similar jobs</h2>
                            <div class="mt-4 grid gap-3 sm:grid-cols-3">
                                @foreach (array_slice(array_filter($portal['jobs'], fn ($item) => $item['slug'] !== $job['slug']), 0, 3) as $similar)
                                    <a href="/jobs/{{ $similar['slug'] }}" class="rounded border border-slate-200 p-4 hover:border-[#2a7190]">
                                        <p class="font-semibold">{{ $similar['title'] }}</p>
                                        <p class="mt-1 text-xs text-slate-600">{{ $similar['city'] }}, {{ $similar['country'] }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    </div>
                </article>
                <aside id="apply" class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                    <h2 class="text-lg font-bold">Apply now</h2>
                    <dl class="mt-4 grid gap-3 text-sm">
                        <div><dt class="text-slate-500">Salary</dt><dd class="font-semibold">{{ $job['salary'] }}</dd></div>
                        <div><dt class="text-slate-500">Employment type</dt><dd class="font-semibold">{{ $job['type'] }}</dd></div>
                        <div><dt class="text-slate-500">Vacancies</dt><dd class="font-semibold">{{ $job['vacancies'] }}</dd></div>
                        <div><dt class="text-slate-500">Deadline</dt><dd class="font-semibold">{{ $job['deadline'] }}</dd></div>
                        <div><dt class="text-slate-500">Employer status</dt><dd class="font-semibold text-[#2a7190]">Verified employer</dd></div>
                    </dl>
                    <div class="mt-5 grid gap-2">
                        <a href="/login" class="rounded border border-slate-300 bg-white px-4 py-2 text-left text-sm font-semibold">Apply with portal profile</a>
                        <a href="/cv-builder" class="rounded border border-slate-300 bg-white px-4 py-2 text-left text-sm font-semibold">Apply with CV</a>
                        <a href="/video-profile" class="rounded border border-slate-300 bg-white px-4 py-2 text-left text-sm font-semibold">Apply with video profile</a>
                        <a href="/portfolio" class="rounded border border-slate-300 bg-white px-4 py-2 text-left text-sm font-semibold">Apply with portfolio</a>
                        <a href="/login" class="rounded border border-slate-300 bg-white px-4 py-2 text-left text-sm font-semibold">Apply with cover letter</a>
                        <a href="/login" class="rounded border border-slate-300 bg-white px-4 py-2 text-left text-sm font-semibold">Apply with LinkedIn profile</a>
                    </div>
                    <a href="/login" class="mt-5 block w-full rounded bg-[#2a7190] px-4 py-3 text-center font-semibold text-white">Apply now</a>
                    <a href="{{ route('report.create', ['type' => 'job', 'subject' => $job['slug']]) }}" class="mt-3 block w-full rounded border border-red-200 bg-red-50 px-4 py-2 text-center text-sm font-semibold text-red-700">Report job</a>
                    <p class="mt-4 text-xs leading-5 text-slate-500">The portal does not guarantee visa approval, job placement, salary, or migration outcome. It connects job seekers and employers.</p>
                </aside>
            </div>
        </div>
    </section>
</x-layouts.app>
