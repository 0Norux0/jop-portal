<x-layouts.app title="Premium Tools">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-10 sm:px-6 lg:grid-cols-[280px_1fr] lg:px-8">
            @include('employer.partials.nav')
            <section class="rounded-lg bg-white p-6 shadow-sm">
                <p class="font-semibold text-[#2a7190]">Premium</p>
                <h1 class="mt-1 text-3xl font-extrabold">Growth tools</h1>
                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    @foreach ([
                        'Boosted reach' => 'Increase job visibility when promotions are enabled.',
                        'Advanced candidate filters' => 'Unlock deeper candidate search segments later.',
                        'Company analytics' => 'Track company page and job performance.',
                        'Applicant insights' => 'Rank and compare applicants after the matching module is added.',
                    ] as $title => $copy)
                        <article class="rounded border border-slate-200 p-5">
                            <h2 class="font-bold">{{ $title }}</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $copy }}</p>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>
    </section>
</x-layouts.app>
