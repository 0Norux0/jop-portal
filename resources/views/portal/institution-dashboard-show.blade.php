<x-layouts.app :title="$dashboard['name'].' Dashboard'">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <a href="/institution-dashboards" class="text-sm font-semibold text-[#2a7190]">All institution dashboards</a>
            <div class="mt-4 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="font-semibold text-[#2a7190]">{{ $dashboard['location'] }}</p>
                    <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $dashboard['name'] }} dashboard</h1>
                    <p class="mt-4 max-w-3xl leading-7 text-slate-700">Placement and graduate employability metrics for optional verified institution talent pools.</p>
                </div>
                <span class="rounded-full bg-white px-4 py-2 text-sm font-bold text-[#2a7190] shadow-sm">AI career stats included</span>
            </div>

            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    'Graduate employment rate' => $dashboard['graduate_employment_rate'],
                    'Average salary' => $dashboard['average_salary'],
                    'Placement percentage' => $dashboard['placement_percentage'],
                    'Employer partners' => $dashboard['employer_partners'],
                ] as $label => $value)
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <p class="text-sm text-slate-500">{{ $label }}</p>
                        <p class="mt-2 text-3xl font-extrabold text-[#2a7190]">{{ $value }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 grid gap-5 lg:grid-cols-2">
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Top industries</h2>
                    <div class="mt-5 flex flex-wrap gap-2">
                        @foreach ($dashboard['top_industries'] as $industry)
                            <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-sm font-semibold text-[#2a7190]">{{ $industry }}</span>
                        @endforeach
                    </div>
                </section>
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">AI career statistics</h2>
                    <div class="mt-5 grid gap-3">
                        @foreach ($dashboard['ai_career_statistics'] as $label => $value)
                            <div class="flex items-center justify-between rounded border border-slate-200 bg-slate-50 px-4 py-3">
                                <span class="text-sm font-semibold">{{ $label }}</span>
                                <span class="font-bold text-[#2a7190]">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </section>
</x-layouts.app>
