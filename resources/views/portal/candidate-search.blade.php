@php($pageContent = \App\Support\PageContent::get('candidate-search'))
<x-layouts.app :title="$pageContent['title']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
            <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $pageContent['title'] }}</h1>
            <p class="mt-4 max-w-3xl leading-7 text-slate-700">{{ $pageContent['description'] }}</p>

            <form method="GET" class="mt-8 rounded-lg bg-white p-5 shadow-sm">
                <div class="grid gap-3 md:grid-cols-[1fr_auto]">
                    <input name="q" value="{{ $filters['q'] ?? '' }}" class="rounded border-slate-300 px-4 py-3 text-sm" placeholder="Search candidates, skills, or job titles">
                    <button class="rounded bg-[#2a7190] px-5 py-3 text-sm font-bold text-white">Search</button>
                </div>
                <details class="mt-4" @if(collect($filters ?? [])->except('q')->filter()->isNotEmpty()) open @endif>
                    <summary class="cursor-pointer text-sm font-bold text-[#2a7190]">Filter</summary>
                    <div class="mt-4 grid gap-3 md:grid-cols-2">
                        <select name="country" class="rounded border-slate-300 px-4 py-3 text-sm">
                            <option value="">All countries</option>
                            @foreach ($portal['countries'] as $country)
                                <option value="{{ $country }}" @selected(($filters['country'] ?? '') === $country)>{{ $country }}</option>
                            @endforeach
                        </select>
                        <select name="badge" class="rounded border-slate-300 px-4 py-3 text-sm">
                            <option value="">Any profile signal</option>
                            @foreach ($portal['badges'] as $badge)
                                <option value="{{ $badge }}" @selected(($filters['badge'] ?? '') === $badge)>{{ $badge }}</option>
                            @endforeach
                        </select>
                    </div>
                </details>
            </form>

            <div class="mt-6 rounded-lg border border-[#2a7190]/20 bg-white p-5 text-sm leading-6 text-slate-700">
                <strong>Candidate consent gate:</strong> full contact details are revealed only through approved employer access, candidate visibility settings, or explicit candidate consent. Public previews keep sensitive contact information hidden.
            </div>
            <div class="mt-8 grid gap-5 lg:grid-cols-3">
                @forelse ($portal['candidates'] as $candidate)
                    <article class="rounded-lg bg-white p-5 shadow-sm">
                        <h2 class="font-bold">{{ $candidate['name'] }}</h2>
                        <p class="mt-1 text-sm text-slate-600">{{ $candidate['headline'] }} · {{ $candidate['country'] }}</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($candidate['badges'] as $badge)
                                <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-xs font-bold text-[#2a7190]">{{ $badge }}</span>
                            @endforeach
                        </div>
                        <p class="mt-4 text-sm text-slate-600">{{ implode(' · ', $candidate['skills']) }}</p>
                        <div class="mt-5 flex flex-wrap gap-2">
                            <a href="/login" class="rounded bg-[#2a7190] px-4 py-2 text-xs font-semibold text-white">Request contact access</a>
                            <a href="{{ route('report.create', ['type' => 'candidate', 'subject' => $candidate['name']]) }}" class="rounded border border-red-200 bg-red-50 px-4 py-2 text-xs font-semibold text-red-700">Report candidate</a>
                        </div>
                    </article>
                @empty
                    <p class="rounded-lg bg-white p-6 text-sm text-slate-600 shadow-sm lg:col-span-3">No candidates match these filters yet.</p>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.app>