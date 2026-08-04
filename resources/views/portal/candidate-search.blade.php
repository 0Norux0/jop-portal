@php($pageContent = \App\Support\PageContent::get('candidate-search'))
<x-layouts.app :title="$pageContent['title']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
            <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $pageContent['title'] }}</h1>
            <p class="mt-4 max-w-3xl leading-7 text-slate-700">{{ $pageContent['description'] }}</p>
            <div class="mt-8 grid gap-3 rounded-lg bg-white p-5 shadow-sm md:grid-cols-4">
                @foreach (['Country', 'Current location', 'Preferred work country', 'Job title', 'Skills', 'Experience', 'Education', 'Certification', 'Language', 'Salary expectation', 'Notice period', 'Visa status', 'Work permit status', 'Industry', 'Employment type'] as $filter)
                    <select class="rounded bg-slate-50 text-sm"><option>{{ $filter }}</option></select>
                @endforeach
            </div>
            <div class="mt-5 flex flex-wrap gap-2 text-xs">
                @foreach (['Willing to relocate', 'Remote available', 'Video profile available', 'Portfolio available', 'Verified profile', 'ICSA/NAS verified', 'Recently active', 'Open to work', 'Basic free preview', 'Advanced paid/premium search'] as $chip)
                    <label class="rounded-full border border-slate-200 bg-white px-3 py-2 font-semibold text-slate-700">
                        <input type="checkbox" class="mr-1 align-middle"> {{ $chip }}
                    </label>
                @endforeach
            </div>
            <div class="mt-6 rounded-lg border border-[#2a7190]/20 bg-white p-5 text-sm leading-6 text-slate-700">
                <strong>Candidate consent gate:</strong> full contact details are revealed only through approved employer access, candidate visibility settings, or explicit candidate consent. Public previews keep sensitive contact information hidden.
            </div>
            <div class="mt-8 grid gap-5 lg:grid-cols-3">
                @foreach ($portal['candidates'] as $candidate)
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
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.app>
