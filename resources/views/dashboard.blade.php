<x-layouts.app title="Dashboard">
    @php
        $missingProfileItems = collect($profileCompletion['missing'] ?? []);
        $visibleProfileItems = $missingProfileItems->take(3);
        $hiddenProfileItemCount = $missingProfileItems->count() - $visibleProfileItems->count();
    @endphp
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
            <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
                <div>
                    <p class="font-semibold text-[#2a7190]">Job seeker dashboard</p>
                    <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">Welcome, {{ auth()->user()->name }}</h1>
                    <p class="mt-3 max-w-3xl text-slate-600">Track your profile, applications, job alerts, employer activity, and career improvements from one place.</p>
                </div>
            </div>

            <div class="mt-8 grid items-start gap-5 xl:grid-cols-[minmax(0,1fr)_520px]">
                <aside class="rounded-lg bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold">Profile completion</h2>
                            <p class="mt-1 text-sm text-slate-600">CV, skills, video, portfolio, and preferences</p>
                        </div>
                        <p class="text-3xl font-extrabold text-[#2a7190]">{{ $profileCompletion['percent'] ?? 0 }}%</p>
                    </div>
                    <div class="mt-5 h-3 rounded-full bg-slate-100">
                        <div class="h-3 rounded-full bg-[#2a7190]" style="width: {{ $profileCompletion['percent'] ?? 0 }}%"></div>
                    </div>
                    <div class="mt-5 flex flex-wrap gap-2">
                        @forelse ($visibleProfileItems as $improvement)
                            <a href="{{ route('candidate.profile') }}" class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold hover:border-[#2a7190] hover:text-[#2a7190]">{{ $improvement }}</a>
                        @empty
                            <a href="{{ route('candidate.profile') }}" class="rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-800">View completed profile</a>
                        @endforelse
                    </div>
                    @if ($hiddenProfileItemCount > 0)
                        <a href="{{ route('candidate.profile') }}" class="mt-4 inline-flex text-sm font-semibold text-[#2a7190]">
                            {{ $hiddenProfileItemCount }} more profile items
                        </a>
                    @endif
                </aside>

                <div class="grid auto-rows-min gap-5 sm:grid-cols-2">
                    @foreach ([
                        'Profile views' => '42',
                        'CV downloads' => '8',
                        'Video views' => '15',
                        'Job alerts' => (string) (($alerts ?? collect())->count()),
                    ] as $label => $value)
                        <div class="min-h-[116px] rounded-lg bg-white p-6 shadow-sm">
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
                        @forelse (($savedJobs ?? collect()) as $savedJob)
                            <a href="/jobs/{{ $savedJob->job?->slug }}" class="rounded border border-slate-200 p-4 hover:border-[#2a7190]">
                                <p class="font-semibold">{{ $savedJob->job?->title }}</p>
                                <p class="mt-1 text-sm text-slate-600">Saved {{ $savedJob->saved_at?->diffForHumans() ?? $savedJob->created_at->diffForHumans() }}</p>
                            </a>
                        @empty
                            <a href="{{ route('candidate.saved-jobs') }}" class="rounded border border-dashed border-slate-300 p-4 text-sm font-semibold text-slate-600">No saved jobs yet</a>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Applied jobs</h2>
                    <div class="mt-4 grid gap-3">
                        @forelse (($applications ?? collect()) as $application)
                            <a href="/jobs/{{ $application->job?->slug }}" class="rounded border border-slate-200 p-4 hover:border-[#2a7190]">
                                <p class="font-semibold">{{ $application->job?->title }}</p>
                                <p class="mt-1 text-sm text-[#2a7190]">{{ str($application->status)->headline() }}</p>
                            </a>
                        @empty
                            <a href="{{ route('candidate.applications') }}" class="rounded border border-dashed border-slate-300 p-4 text-sm font-semibold text-slate-600">No applications yet</a>
                        @endforelse
                    </div>
                </section>
            </div>

            <div class="mt-8 grid gap-5 lg:grid-cols-2">
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Interview invitations</h2>
                    <div class="mt-3 grid gap-3">
                        @forelse (($messages ?? collect()) as $message)
                            <a href="{{ route('candidate.messages') }}" class="rounded border border-slate-200 p-4 hover:border-[#2a7190]">
                                <p class="font-semibold">{{ $message->employer?->name }}</p>
                                <p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $message->body }}</p>
                            </a>
                        @empty
                            <p class="text-sm leading-6 text-slate-600">Employer messages and interview requests will appear here.</p>
                        @endforelse
                    </div>
                    <a href="{{ route('candidate.messages') }}" class="mt-4 inline-flex rounded border border-[#2a7190] px-4 py-2 text-sm font-semibold text-[#2a7190]">Open messages</a>
                </section>
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Career tips</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Add a 60-second video profile and one country-specific CV version to improve shortlist chances.</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('candidate.profile') }}" class="inline-flex rounded bg-[#2a7190] px-4 py-2 text-sm font-semibold text-white">Edit profile</a>
                        <a href="/career-coach" class="inline-flex rounded border border-[#2a7190] px-4 py-2 text-sm font-semibold text-[#2a7190]">Open career coach</a>
                    </div>
                </section>
            </div>
        </div>
    </section>
</x-layouts.app>
