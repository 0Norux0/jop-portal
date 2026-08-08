<x-layouts.app title="Dashboard">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
            <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
                <div>
                    <p class="font-semibold text-[#2a7190]">Job seeker dashboard</p>
                    <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">Welcome, {{ auth()->user()->name }}</h1>
                    <p class="mt-3 max-w-3xl text-slate-600">Track your profile, applications, job alerts, employer activity, and career improvements from one place.</p>
                </div>
            </div>

            <div class="mt-8 grid gap-5 xl:grid-cols-[340px_1fr]">
                <aside class="rounded-lg bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold">Profile completion</h2>
                            <p class="mt-1 text-sm text-slate-600">CV, skills, video, portfolio, and preferences</p>
                        </div>
                        <p class="text-3xl font-extrabold text-[#2a7190]">78%</p>
                    </div>
                    <div class="mt-5 h-3 rounded-full bg-slate-100">
                        <div class="h-3 w-[78%] rounded-full bg-[#2a7190]"></div>
                    </div>
                    <div class="mt-6 grid gap-3">
                        @foreach ([
                            ['Add one more certificate', '/candidate-profile'],
                            ['Upload portfolio samples', '/portfolio'],
                            ['Confirm phone verification', '/candidate-verification'],
                            ['Add preferred salary range', '/candidate-profile'],
                        ] as [$improvement, $url])
                            <a href="{{ $url }}" class="rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold hover:border-[#2a7190] hover:text-[#2a7190]">{{ $improvement }}</a>
                        @endforeach
                    </div>
                </aside>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ([
                        'Profile views' => '42',
                        'CV downloads' => '8',
                        'Video views' => '15',
                        'Job alerts' => '6',
                    ] as $label => $value)
                        <div class="rounded-lg bg-white p-6 shadow-sm">
                            <p class="text-3xl font-extrabold text-[#2a7190]">{{ $value }}</p>
                            <p class="mt-1 text-sm text-slate-600">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 grid gap-5 lg:grid-cols-3">
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Recommended jobs</h2>
                    <p class="mt-1 text-sm text-slate-600">Refreshed from the latest published jobs.</p>
                    <div class="mt-4 grid gap-3">
                        @foreach (array_slice($portal['jobs'], 0, 3) as $job)
                            <a href="/jobs/{{ $job['slug'] }}" class="rounded border border-slate-200 p-4 hover:border-[#2a7190]">
                                <p class="font-semibold">{{ $job['title'] }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $job['city'] }}, {{ $job['country'] }}</p>
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Saved jobs</h2>
                    <div class="mt-4 grid gap-3">
                        @foreach (array_slice($portal['jobs'], 1, 2) as $job)
                            <a href="/jobs/{{ $job['slug'] }}" class="rounded border border-slate-200 p-4 hover:border-[#2a7190]">
                                <p class="font-semibold">{{ $job['title'] }}</p>
                                <p class="mt-1 text-sm text-slate-600">Saved for later review</p>
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Applied jobs</h2>
                    <div class="mt-4 grid gap-3">
                        @foreach (array_slice($portal['jobs'], 0, 2) as $job)
                            <a href="/jobs/{{ $job['slug'] }}#apply" class="rounded border border-slate-200 p-4 hover:border-[#2a7190]">
                                <p class="font-semibold">{{ $job['title'] }}</p>
                                <p class="mt-1 text-sm text-[#2a7190]">Application status active</p>
                            </a>
                        @endforeach
                    </div>
                </section>
            </div>

            <div class="mt-8 grid gap-5 lg:grid-cols-2">
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Interview invitations</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Interview tools will appear here when real employer scheduling is connected.</p>
                    <a href="/jobs" class="mt-4 inline-flex rounded border border-[#2a7190] px-4 py-2 text-sm font-semibold text-[#2a7190]">Browse jobs</a>
                </section>
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Career tips</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Add a 60-second video profile and one country-specific CV version to improve shortlist chances.</p>
                    <a href="/career-coach" class="mt-4 inline-flex rounded bg-[#2a7190] px-4 py-2 text-sm font-semibold text-white">Open career coach</a>
                </section>
            </div>
        </div>
    </section>
</x-layouts.app>
