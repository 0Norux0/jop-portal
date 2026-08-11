@php($pageContent = \App\Support\PageContent::get('employer-verification'))
<x-layouts.app :title="$pageContent['title']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
            <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $pageContent['title'] }}</h1>
            <p class="mt-4 max-w-3xl leading-7 text-slate-700">{{ $pageContent['description'] }}</p>
            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    'Unverified',
                    'Email verified',
                    'Phone verified',
                    'Company document verified',
                    'Recruitment agency verified',
                    'Trusted employer',
                    'Trusted employer',
                ] as $level)
                    <div class="rounded-lg bg-white p-5 shadow-sm">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#e9f3f7] text-[#2a7190]">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 3 5 6v5c0 4.5 2.9 8.4 7 10 4.1-1.6 7-5.5 7-10V6l-7-3Z" stroke="currentColor" stroke-width="2"/>
                                <path d="m9 12 2 2 4-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <h2 class="mt-4 font-bold">{{ $level }}</h2>
                    </div>
                @endforeach
            </div>
            <section class="mt-8 rounded-lg bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold">Documents and review</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach (['Commercial license', 'Company registration', 'Recruitment agency license', 'Domain/email verification'] as $item)
                        <div class="rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold">{{ $item }}</div>
                    @endforeach
                </div>
            </section>
            <section class="mt-8 rounded-lg bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold">Employer trust score inputs</h2>
                        <p class="mt-2 text-sm text-slate-600">Verified employers earn trust through documents, domain checks, safe job rules, payment records, and review history.</p>
                    </div>
                    <span class="rounded-full bg-[#e9f3f7] px-4 py-2 text-sm font-bold text-[#2a7190]">Example score: {{ \App\Support\TrustScore::employer(['company_documents' => true, 'verified_domain' => true, 'clear_job_rules' => true, 'payment_record' => true, 'review_history' => true, 'fake_detection_clear' => true]) }} / 100</span>
                </div>
                <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach (\App\Support\TrustScore::employerInputs() as $input)
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
