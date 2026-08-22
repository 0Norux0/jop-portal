@php($site = \App\Support\SiteContent::load())
<x-layouts.app title="About Us">
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <p class="font-semibold text-[#2a7190]">About {{ $site['brand']['name'] }}</p>
            <h1 class="mt-2 max-w-3xl text-4xl font-extrabold text-slate-950">A practical hiring platform for real international job search</h1>
            <p class="mt-4 max-w-3xl leading-7 text-slate-600">{{ $site['brand']['description'] ?: 'We help job seekers build trustworthy profiles and help employers review applicants with clearer information before making contact.' }}</p>

            <div class="mt-10 grid gap-5 md:grid-cols-3">
                @foreach ([
                    ['Clear profiles', 'Candidates can add CVs, video CV links, experience, education, projects, certificates, and portfolio proof.'],
                    ['Safer hiring', 'Reports, verification states, and admin review tools help reduce fake jobs and unsafe recruitment claims.'],
                    ['Useful matching', 'Search and recommendations use current job and candidate data instead of static placeholder lists.'],
                ] as [$title, $copy])
                    <article class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                        <h2 class="font-bold text-slate-950">{{ $title }}</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $copy }}</p>
                    </article>
                @endforeach
            </div>

            <div class="mt-10 rounded-lg border border-slate-200 bg-[#f7f7f7] p-6">
                <h2 class="text-xl font-bold">What we are building</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach (['Global job search', 'Candidate profiles', 'Employer workspaces', 'Admin safety review'] as $item)
                        <div class="rounded border border-slate-200 bg-white px-4 py-3 text-sm font-semibold">{{ $item }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>