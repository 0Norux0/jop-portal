<x-layouts.app title="Find Candidates">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-10 sm:px-6 lg:grid-cols-[280px_1fr] lg:px-8">
            @include('employer.partials.nav')
            <div class="space-y-5">
                @if (session('status'))
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
                @endif
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <p class="font-semibold text-[#2a7190]">Hire talent</p>
                    <h1 class="mt-1 text-3xl font-extrabold">Candidate discovery</h1>
                    <form method="GET" class="mt-6 grid gap-3 md:grid-cols-4">
                        <select name="category" class="rounded border-slate-300 px-4 py-3 text-sm">
                            <option value="">All categories</option>
                            @foreach ($portal['categories'] as $category)
                                <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                        <select name="country" class="rounded border-slate-300 px-4 py-3 text-sm">
                            <option value="">All countries</option>
                            @foreach ($portal['countries'] as $country)
                                <option value="{{ $country }}" @selected(($filters['country'] ?? '') === $country)>{{ $country }}</option>
                            @endforeach
                        </select>
                        <input name="skill" value="{{ $filters['skill'] ?? '' }}" class="rounded border-slate-300 px-4 py-3 text-sm" placeholder="Skill keyword">
                        <button class="rounded bg-[#2a7190] px-4 py-3 text-sm font-bold text-white">Search</button>
                    </form>
                    <p class="mt-4 rounded border border-[#2a7190]/20 bg-[#e9f3f7] p-4 text-sm leading-6 text-slate-700">Contact details stay protected. Save candidates here, then request contact access through the approved employer workflow.</p>
                </section>
                <div class="grid gap-4 lg:grid-cols-2">
                    @forelse ($candidates as $candidate)
                        <article class="rounded-lg bg-white p-5 shadow-sm">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h2 class="text-lg font-bold">{{ $candidate->full_name }}</h2>
                                    <p class="mt-1 text-sm text-slate-600">{{ $candidate->headline }} · {{ $candidate->city }}, {{ $candidate->country }}</p>
                                </div>
                                <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-xs font-bold text-[#2a7190]">{{ $candidate->trust_score }} trust</span>
                            </div>
                            <p class="mt-4 text-sm leading-6 text-slate-700">{{ $candidate->bio }}</p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach (($candidate->skills ?? []) as $skill)
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold">{{ $skill }}</span>
                                @endforeach
                            </div>
                            <div class="mt-5 flex flex-wrap gap-2">
                                @if ($candidate->is_public && $candidate->slug)
                                    <a href="{{ route('candidate.public', $candidate->slug) }}" class="rounded border border-[#2a7190] px-4 py-2 text-sm font-bold text-[#2a7190]">Public profile</a>
                                @endif
                                @if ($candidate->cv_path)
                                    <a href="{{ asset($candidate->cv_path) }}" class="rounded border border-slate-200 px-4 py-2 text-sm font-bold">CV</a>
                                @endif
                                @if ($candidate->video_path)
                                    <a href="{{ asset($candidate->video_path) }}" class="rounded border border-slate-200 px-4 py-2 text-sm font-bold">Video</a>
                                @endif
                                @if ($candidate->portfolio_url)
                                    <a href="{{ $candidate->portfolio_url }}" class="rounded border border-slate-200 px-4 py-2 text-sm font-bold">Portfolio</a>
                                @endif
                                <form method="POST" action="{{ route('business.candidates.invite', $candidate) }}">
                                    @csrf
                                    <button class="rounded bg-[#2a7190] px-4 py-2 text-sm font-bold text-white">Save candidate</button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <p class="rounded-lg bg-white p-6 text-sm text-slate-600 shadow-sm">No candidates match these filters yet.</p>
                    @endforelse
                </div>
                {{ $candidates->links() }}
            </div>
        </div>
    </section>
</x-layouts.app>
