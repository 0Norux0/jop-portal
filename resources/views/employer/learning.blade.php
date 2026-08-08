<x-layouts.app title="Employer Learning">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-10 sm:px-6 lg:grid-cols-[280px_1fr] lg:px-8">
            @include('employer.partials.nav')
            <section class="rounded-lg bg-white p-6 shadow-sm">
                <p class="font-semibold text-[#2a7190]">Learning</p>
                <h1 class="mt-1 text-3xl font-extrabold">Employee training tools</h1>
                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    @foreach ([
                        'Hiring manager basics',
                        'Interview training',
                        'Compliance and workplace readiness',
                        'Employee development plans',
                        'Saved training',
                        'Team course assignments',
                    ] as $title)
                        <article class="rounded border border-slate-200 p-5">
                            <h2 class="font-bold">{{ $title }}</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Prepared for future real course content and employer staff access.</p>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>
    </section>
</x-layouts.app>
