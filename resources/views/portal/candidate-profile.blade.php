@php
    $candidate = $portal['candidates'][0] ?? ['name' => 'Candidate Name', 'headline' => 'Professional headline', 'country' => 'Global', 'badges' => [], 'skills' => []];
    $initials = collect(explode(' ', $candidate['name']))->map(fn ($part) => substr($part, 0, 1))->join('');
    $candidateTrustScore = \App\Support\TrustScore::candidate([
        'verified_identity' => true,
        'verified_certificates' => true,
        'portfolio_quality' => true,
        'response_rate' => true,
        'interview_attendance' => true,
        'employer_reviews' => false,
        'fake_detection_clear' => true,
    ]);
@endphp
<x-layouts.app :title="$candidate['name']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[360px_1fr]">
                <aside class="rounded-lg bg-white p-6 shadow-sm">
                    <div class="flex flex-col items-center text-center">
                        <div class="flex h-28 w-28 items-center justify-center rounded-full bg-[#2a7190] text-3xl font-extrabold text-white">{{ $initials }}</div>
                        <h1 class="mt-5 text-2xl font-extrabold">{{ $candidate['name'] }}</h1>
                        <p class="mt-2 font-semibold text-[#2a7190]">{{ $candidate['headline'] }}</p>
                        <p class="mt-2 text-sm text-slate-600">{{ $candidate['country'] }} · Open to work</p>
                    </div>
                    <div class="mt-6 flex flex-wrap justify-center gap-2">
                        @foreach ($candidate['badges'] as $badge)
                            <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-xs font-bold text-[#2a7190]">{{ $badge }}</span>
                        @endforeach
                    </div>
                    <dl class="mt-6 grid gap-3 text-sm">
                        <div><dt class="text-slate-500">Candidate trust score</dt><dd class="font-semibold">{{ $candidateTrustScore }} / 100</dd></div>
                        <div><dt class="text-slate-500">Expected salary</dt><dd class="font-semibold">GBP 2,200 monthly</dd></div>
                        <div><dt class="text-slate-500">Notice period</dt><dd class="font-semibold">Available immediately</dd></div>
                        <div><dt class="text-slate-500">Remote work</dt><dd class="font-semibold">Available</dd></div>
                        <div><dt class="text-slate-500">Relocation</dt><dd class="font-semibold">United Kingdom, Canada, Gulf</dd></div>
                        <div><dt class="text-slate-500">Contact visibility</dt><dd class="font-semibold">Employer-only</dd></div>
                    </dl>
                </aside>

                <main class="grid gap-6">
                    <section class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold">Short bio</h2>
                        <p class="mt-3 leading-7 text-slate-700">Compassionate caregiver with elder-care training, first-aid knowledge, and experience supporting daily routines, mobility, companionship, and safe care documentation.</p>
                    </section>

                    <section class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold">Preferred job titles</h2>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach (['Senior Caregiver', 'Healthcare Assistant', 'Elder Care Support Worker', 'Care Home Assistant'] as $title)
                                <span class="rounded-full border border-slate-200 px-3 py-1 text-sm font-semibold">{{ $title }}</span>
                            @endforeach
                        </div>
                    </section>

                    <div class="grid gap-6 lg:grid-cols-2">
                        <section class="rounded-lg bg-white p-6 shadow-sm">
                            <h2 class="text-xl font-bold">Work experience</h2>
                            <div class="mt-4 border-l-2 border-[#2a7190] pl-4">
                                <p class="font-semibold">Care Assistant · Sunrise Home Care</p>
                                <p class="text-sm text-slate-600">2023 - Present</p>
                                <p class="mt-2 text-sm leading-6 text-slate-700">Supported elderly clients with mobility, hygiene, meals, companionship, and care notes.</p>
                            </div>
                        </section>
                        <section class="rounded-lg bg-white p-6 shadow-sm">
                            <h2 class="text-xl font-bold">Education</h2>
                            <div class="mt-4 border-l-2 border-[#2a7190] pl-4">
                                <p class="font-semibold">Diploma in Health & Social Care</p>
                                <p class="text-sm text-slate-600">Training Institute · 2022</p>
                                <p class="mt-2 text-sm leading-6 text-slate-700">Coursework in safeguarding, patient care, infection control, and communication.</p>
                            </div>
                        </section>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-3">
                        <section class="rounded-lg bg-white p-6 shadow-sm">
                            <h2 class="text-xl font-bold">Skills</h2>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach ($candidate['skills'] as $skill)
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-sm">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </section>
                        <section class="rounded-lg bg-white p-6 shadow-sm">
                            <h2 class="text-xl font-bold">Languages</h2>
                            <ul class="mt-4 grid gap-2 text-sm text-slate-700">
                                <li>English · Professional</li>
                                <li>Urdu · Native</li>
                                <li>Arabic · Basic</li>
                            </ul>
                        </section>
                        <section class="rounded-lg bg-white p-6 shadow-sm">
                            <h2 class="text-xl font-bold">Certifications</h2>
                            <ul class="mt-4 grid gap-2 text-sm text-slate-700">
                                <li>Caregiving Certificate · Verified</li>
                                <li>First Aid Training · Verified</li>
                                <li>Infection Control · Uploaded</li>
                            </ul>
                        </section>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-3">
                        <section class="rounded-lg bg-white p-6 shadow-sm">
                            <h2 class="text-xl font-bold">Training courses</h2>
                            <p class="mt-3 text-sm leading-6 text-slate-700">Safeguarding, dementia support, communication, basic life support, and workplace safety.</p>
                        </section>
                        <section class="rounded-lg bg-white p-6 shadow-sm">
                            <h2 class="text-xl font-bold">Portfolio / projects</h2>
                            <p class="mt-3 text-sm leading-6 text-slate-700">Care plan samples, training assignments, certificates, and professional references can be attached here.</p>
                        </section>
                        <section class="rounded-lg bg-white p-6 shadow-sm">
                            <h2 class="text-xl font-bold">Video introduction</h2>
                            <div class="mt-4 flex aspect-video items-center justify-center rounded bg-[#e9f3f7] text-sm font-semibold text-[#2a7190]">60-second intro preview</div>
                        </section>
                    </div>

                    <section class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold">CV / resume and references</h2>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="rounded border border-slate-200 p-4"><p class="font-semibold">CV uploaded</p><p class="mt-1 text-sm text-slate-600">ATS-friendly PDF</p></div>
                            <div class="rounded border border-slate-200 p-4"><p class="font-semibold">References</p><p class="mt-1 text-sm text-slate-600">Available on request</p></div>
                            <div class="rounded border border-slate-200 p-4"><p class="font-semibold">LinkedIn profile</p><p class="mt-1 text-sm text-slate-600">Available with candidate consent</p></div>
                            <div class="rounded border border-slate-200 p-4"><p class="font-semibold">Preferred countries</p><p class="mt-1 text-sm text-slate-600">UK, Canada, UAE, Kuwait</p></div>
                        </div>
                    </section>

                    <section class="rounded-lg bg-white p-6 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h2 class="text-xl font-bold">Trust score signals</h2>
                                <p class="mt-2 text-sm text-slate-600">Transparent inputs for candidate trust and future ranking.</p>
                            </div>
                            <span class="rounded-full bg-[#e9f3f7] px-4 py-2 text-sm font-bold text-[#2a7190]">{{ $candidateTrustScore }} / 100</span>
                        </div>
                        <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach (\App\Support\TrustScore::candidateInputs() as $input)
                                <div class="rounded border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-sm font-bold">{{ $input['label'] }}</p>
                                    <p class="mt-1 text-xs leading-5 text-slate-600">{{ $input['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </section>
</x-layouts.app>
