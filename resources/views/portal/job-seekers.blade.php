@php($pageContent = \App\Support\PageContent::get('job-seekers'))
<x-layouts.app :title="$pageContent['title']">
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
        <h1 class="mt-2 text-3xl font-bold">{{ $pageContent['title'] }}</h1>
        <p class="mt-3 max-w-3xl text-slate-600">{{ $pageContent['description'] }}</p>
        <div class="mt-5 flex flex-wrap gap-3">
            <a href="/candidate-profile" class="inline-flex rounded bg-[#2a7190] px-5 py-3 text-sm font-semibold text-white">View candidate profile example</a>
            <a href="/cv-builder" class="inline-flex rounded border border-[#2a7190] px-5 py-3 text-sm font-semibold text-[#2a7190]">Open CV builder</a>
            <a href="/video-profile" class="inline-flex rounded border border-[#2a7190] px-5 py-3 text-sm font-semibold text-[#2a7190]">Video profile tools</a>
            <a href="/portfolio" class="inline-flex rounded border border-[#2a7190] px-5 py-3 text-sm font-semibold text-[#2a7190]">Portfolio showcase</a>
        </div>
        <div class="mt-8 grid gap-5 lg:grid-cols-3">
            @foreach (['Candidate profile' => ['Photo', 'Headline', 'Bio', 'Experience', 'Education', 'Skills', 'Languages', 'Expected salary'], 'CV and resume builder' => ['Upload CV', 'Create CV online', 'ATS-friendly format', 'Cover letter builder', 'CV score'], 'Video and portfolio' => ['30/60/90-second intro', 'Admin approval', 'Privacy setting', 'Project links', 'Case studies']] as $title => $items)
                <div class="rounded-lg border border-slate-200 bg-white p-5">
                    <h2 class="font-bold">{{ $title }}</h2>
                    <ul class="mt-4 grid gap-2 text-sm text-slate-700">
                        @foreach ($items as $item)
                            <li>• {{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
        <section class="mt-12 rounded-lg border border-slate-200 bg-white p-6">
            <h2 class="text-2xl font-bold">Who can use the portal</h2>
            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">These audience groups are editable from the Portal Content admin area.</p>
            <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($portal['candidate_types'] as $type)
                    <div class="rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold">{{ $type }}</div>
                @endforeach
            </div>
        </section>
        <h2 class="mt-12 text-2xl font-bold">Candidate badges</h2>
        <div class="mt-5 flex flex-wrap gap-2">
            @foreach ($portal['badges'] as $badge)
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-800">{{ $badge }}</span>
            @endforeach
        </div>
    </section>
</x-layouts.app>
