<x-layouts.app :title="$candidate->full_name">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid items-start gap-6 lg:grid-cols-[340px_1fr]">
                <aside class="rounded-lg bg-white p-6 shadow-sm">
                    <div class="flex h-24 w-24 items-center justify-center rounded-full bg-[#2a7190] text-3xl font-extrabold text-white">
                        {{ collect(explode(' ', $candidate->full_name))->map(fn ($part) => substr($part, 0, 1))->take(2)->join('') }}
                    </div>
                    <h1 class="mt-5 text-3xl font-extrabold">{{ $candidate->full_name }}</h1>
                    <p class="mt-2 font-semibold text-[#2a7190]">{{ $candidate->headline }}</p>
                    <p class="mt-2 text-sm text-slate-600">{{ $candidate->city }}, {{ $candidate->country }}</p>
                    <dl class="mt-6 grid gap-3 text-sm">
                        <div><dt class="text-slate-500">Current status</dt><dd class="font-semibold">{{ $candidate->current_job_title ?: 'Not listed' }}</dd></div>
                        <div><dt class="text-slate-500">Expected salary</dt><dd class="font-semibold">{{ $candidate->expected_salary ?: 'Not listed' }}</dd></div>
                        <div><dt class="text-slate-500">Notice period</dt><dd class="font-semibold">{{ $candidate->notice_period ?: 'Not listed' }}</dd></div>
                        <div><dt class="text-slate-500">Availability</dt><dd class="font-semibold">{{ str($candidate->availability_status)->replace('_', ' ')->headline() ?: 'Not listed' }}</dd></div>
                        <div><dt class="text-slate-500">Profile completion</dt><dd class="font-semibold">{{ $completion['percent'] }}%</dd></div>
                    </dl>
                    @if ($candidate->cv_path)
                        <a href="{{ asset($candidate->cv_path) }}" class="mt-5 inline-flex rounded bg-[#2a7190] px-4 py-2 text-sm font-bold text-white">View CV</a>
                    @endif
                </aside>

                <main class="space-y-6">
                    <section class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold">About</h2>
                        <p class="mt-3 leading-7 text-slate-700">{{ $candidate->bio ?: 'No profile summary added yet.' }}</p>
                        <div class="mt-5 flex flex-wrap gap-2">
                            @foreach (($candidate->skills ?? []) as $skill)
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold">Work preferences</h2>
                        <dl class="mt-4 grid gap-4 text-sm sm:grid-cols-2">
                            <div><dt class="text-slate-500">Preferred locations</dt><dd class="font-semibold">{{ implode(', ', $candidate->preferred_locations ?? []) ?: 'Not listed' }}</dd></div>
                            <div><dt class="text-slate-500">Employment type</dt><dd class="font-semibold">{{ str($candidate->employment_type_preference)->replace('_', ' ')->headline() ?: 'Not listed' }}</dd></div>
                            <div><dt class="text-slate-500">Work mode</dt><dd class="font-semibold">{{ str($candidate->work_mode_preference)->replace('_', ' ')->headline() ?: 'Not listed' }}</dd></div>
                            <div><dt class="text-slate-500">Work authorisation</dt><dd class="font-semibold">{{ $candidate->work_authorization ?: 'Not listed' }}</dd></div>
                            <div><dt class="text-slate-500">Visa requirements</dt><dd class="font-semibold">{{ $candidate->visa_requirements ?: 'Not listed' }}</dd></div>
                            <div><dt class="text-slate-500">Relocation</dt><dd class="font-semibold">{{ str($candidate->relocation_preference)->replace('_', ' ')->headline() ?: 'Not listed' }}</dd></div>
                            <div class="sm:col-span-2"><dt class="text-slate-500">Languages</dt><dd class="font-semibold">{{ implode(', ', $candidate->languages ?? []) ?: 'Not listed' }}</dd></div>
                        </dl>
                    </section>

                    @if ($candidate->video_path)
                        <section class="rounded-lg bg-white p-6 shadow-sm">
                            <h2 class="text-xl font-bold">Video profile</h2>
                            <video src="{{ asset($candidate->video_path) }}" controls class="mt-4 w-full rounded bg-black"></video>
                        </section>
                    @endif

                    <div class="grid gap-6 lg:grid-cols-2">
                        @include('candidate.partials.public-section', ['title' => 'Experience', 'items' => $candidate->experiences])
                        @include('candidate.partials.public-section', ['title' => 'Education', 'items' => $candidate->educations])
                        @include('candidate.partials.public-section', ['title' => 'Certificates', 'items' => $candidate->certificates])
                        @include('candidate.partials.public-section', ['title' => 'Projects', 'items' => $candidate->projects])
                        @include('candidate.partials.public-section', ['title' => 'Portfolio', 'items' => $candidate->portfolioItems])
                    </div>
                </main>
            </div>
        </div>
    </section>
</x-layouts.app>
