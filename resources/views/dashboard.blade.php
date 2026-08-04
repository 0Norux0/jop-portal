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

            <div class="mt-8 grid gap-5 lg:grid-cols-[360px_1fr]">
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
                        @foreach (['Add one more certificate', 'Upload portfolio samples', 'Confirm phone verification', 'Add preferred salary range'] as $improvement)
                            <div class="rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold">{{ $improvement }}</div>
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
                            <div class="rounded border border-slate-200 p-4">
                                <p class="font-semibold">{{ $job['title'] }}</p>
                                <p class="mt-1 text-sm text-slate-600">Saved for later review</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Applied jobs</h2>
                    <div class="mt-4 grid gap-3">
                        @foreach (array_slice($portal['jobs'], 0, 2) as $job)
                            <div class="rounded border border-slate-200 p-4">
                                <p class="font-semibold">{{ $job['title'] }}</p>
                                <p class="mt-1 text-sm text-[#2a7190]">Application status active</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <section class="mt-8 rounded-lg bg-white p-6 shadow-sm">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <h2 class="text-xl font-bold">Application status pipeline</h2>
                        <p class="mt-1 text-sm text-slate-600">Every application can move through these statuses.</p>
                    </div>
                    <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-sm font-bold text-[#2a7190]">Current: Shortlisted</span>
                </div>
                <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                    @foreach (['Applied', 'Viewed', 'Shortlisted', 'Interview invited', 'Interview completed', 'Selected', 'Offer received', 'Hired', 'Rejected', 'Withdrawn'] as $status)
                        <div class="rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold">{{ $status }}</div>
                    @endforeach
                </div>
            </section>

            <div class="mt-8 grid gap-5 lg:grid-cols-3">
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Interview invitations</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Pearl Vista Hotels invited you to a first interview. Confirm availability or suggest another time.</p>
                </section>
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Employer messages</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">2 unread employer messages. Contact details stay protected until sharing is approved.</p>
                </section>
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Career tips</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Add a 60-second video profile and one country-specific CV version to improve shortlist chances.</p>
                </section>
            </div>
        </div>
    </section>
</x-layouts.app>
