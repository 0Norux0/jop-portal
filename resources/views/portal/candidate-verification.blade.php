@php($pageContent = \App\Support\PageContent::get('candidate-verification'))
<x-layouts.app :title="$pageContent['title']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
            <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $pageContent['title'] }}</h1>
            <p class="mt-4 max-w-3xl leading-7 text-slate-700">{{ $pageContent['description'] }}</p>
            <div class="mt-8 grid gap-5 lg:grid-cols-3">
                @foreach ([
                    'General verification' => ['Email verified', 'Phone verified', 'Identity verified, optional', 'Certificate verified', 'Skill verified', 'Video approved', 'Portfolio approved'],
                    'ICSA / NAS graduate trust' => ['ICSA graduate verified', 'NAS graduate verified', 'Course completed', 'Certificate number verified', 'Branch verified', 'Completion date verified'],
                    'Admin workflow' => ['Submitted documents', 'Reviewer notes', 'Approved badges', 'Rejected items', 'Audit history', 'Candidate notification'],
                ] as $title => $items)
                    <section class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold">{{ $title }}</h2>
                        <div class="mt-4 grid gap-3">
                            @foreach ($items as $item)
                                <div class="rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold">{{ $item }}</div>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
            <section class="mt-8 rounded-lg bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold">Candidate trust score inputs</h2>
                        <p class="mt-2 text-sm text-slate-600">These inputs feed the transparent rule-based candidate trust score.</p>
                    </div>
                    <span class="rounded-full bg-[#e9f3f7] px-4 py-2 text-sm font-bold text-[#2a7190]">Example score: {{ \App\Support\TrustScore::candidate(['verified_identity' => true, 'verified_certificates' => true, 'portfolio_quality' => true, 'response_rate' => true, 'interview_attendance' => true, 'employer_reviews' => false, 'fake_detection_clear' => true]) }} / 100</span>
                </div>
                <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach (\App\Support\TrustScore::candidateInputs() as $input)
                        <div class="rounded border border-slate-200 bg-slate-50 p-4">
                            <p class="text-sm font-bold">{{ $input['label'] }}</p>
                            <p class="mt-1 text-xs leading-5 text-slate-600">{{ $input['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </section>
</x-layouts.app>
