<x-layouts.app title="Employer Business Dashboard">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
            @endif

            <div class="grid gap-6 lg:grid-cols-[280px_1fr]">
                @include('employer.partials.nav')

                <div class="space-y-6">
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <p class="font-semibold text-[#2a7190]">Employer workspace</p>
                        <div class="mt-2 flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
                            <div>
                                <h1 class="text-3xl font-extrabold text-slate-950">Welcome back, {{ auth()->user()->name }}</h1>
                                <p class="mt-2 max-w-2xl text-slate-600">Manage hiring, company presence, applicants, candidate discovery, and account controls from one place.</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('business.company') }}" class="rounded border border-[#2a7190] px-4 py-2 text-sm font-bold text-[#2a7190]">Create Company Page</a>
                                <a href="{{ route('business.jobs') }}" class="rounded bg-[#2a7190] px-4 py-2 text-sm font-bold text-white">Post a Job</a>

                            </div>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        @foreach ($stats as $label => $value)
                            <div class="rounded-lg bg-white p-5 shadow-sm">
                                <p class="text-3xl font-extrabold text-[#2a7190]">{{ $value }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>


                    <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
                        <section class="rounded-lg bg-white p-6 shadow-sm">
                            <div class="flex items-center justify-between gap-3">
                                <h2 class="text-xl font-bold">Hiring command center</h2>
                                <a href="{{ route('business.jobs') }}" class="text-sm font-bold text-[#2a7190]">Manage jobs</a>
                            </div>
                            <div class="mt-5 grid gap-3">
                                @forelse ($jobs->take(5) as $job)
                                    <a href="{{ route('jobs.show', $job->slug) }}" class="rounded border border-slate-200 p-4 hover:border-[#2a7190]">
                                        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                                            <div>
                                                <p class="font-bold">{{ $job->title }}</p>
                                                <p class="mt-1 text-sm text-slate-600">{{ $job->city }}, {{ $job->country }} · {{ str($job->work_mode)->replace('_', '-')->title() }}</p>
                                            </div>
                                            <div class="flex gap-2 text-xs font-bold">
                                                <span class="rounded-full bg-slate-100 px-3 py-1">{{ str($job->status)->headline() }}</span>
                                                <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-[#2a7190]">{{ $job->applications_count }} applicants</span>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="rounded border border-dashed border-slate-300 p-6 text-center">
                                        <p class="font-bold">No jobs yet</p>
                                        <p class="mt-1 text-sm text-slate-600">Post your first role and it can appear on the public job board.</p>
                                        <a href="{{ route('business.jobs') }}" class="mt-4 inline-flex rounded bg-[#2a7190] px-4 py-2 text-sm font-bold text-white">Post a Job</a>
                                    </div>
                                @endforelse
                            </div>
                        </section>

                        <section class="rounded-lg bg-white p-6 shadow-sm">
                            <h2 class="text-xl font-bold">Business tools</h2>
                            <div class="mt-4 grid gap-3">
                                @foreach ([
                                    ['Hire talent', 'Search and save verified candidates.', 'business.candidates'],
                                    ['Billing & Account', 'Billing email and account settings.', 'business.billing'],
                                ] as [$title, $copy, $route])
                                    <a href="{{ route($route) }}" class="rounded border border-slate-200 p-4 hover:border-[#2a7190]">
                                        <p class="font-bold">{{ $title }}</p>
                                        <p class="mt-1 text-sm text-slate-600">{{ $copy }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    </div>


                    <section class="rounded-lg bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-xl font-bold">Recent applicants</h2>
                            <a href="{{ route('business.applicants') }}" class="text-sm font-bold text-[#2a7190]">View all</a>
                        </div>
                        <div class="mt-4 grid gap-3">
                            @forelse ($applications as $application)
                                <a href="{{ route('business.applicants', ['status' => $application->status]) }}" class="block rounded border border-slate-200 p-4 hover:border-[#2a7190]">
                                    <p class="font-bold">{{ $application->candidate_name }}</p>
                                    <p class="mt-1 text-sm text-slate-600">{{ $application->job?->title }} · {{ str($application->status)->headline() }}</p>
                                </a>
                            @empty
                                <p class="rounded border border-dashed border-slate-300 p-5 text-sm text-slate-600">Applicants will appear here once candidates apply to your jobs.</p>
                            @endforelse
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
