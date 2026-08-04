@php($pageContent = \App\Support\PageContent::get('cv-builder'))
<x-layouts.app :title="$pageContent['title']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
            <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $pageContent['title'] }}</h1>
            <p class="mt-4 max-w-3xl leading-7 text-slate-700">{{ $pageContent['description'] }}</p>

            <div class="mt-8 grid gap-5 lg:grid-cols-3">
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Upload CV</h2>
                    <div class="mt-4 flex min-h-40 items-center justify-center rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 p-5 text-center">
                        <div>
                            <p class="font-semibold">Drop PDF or DOCX here</p>
                            <p class="mt-1 text-sm text-slate-600">File validation and storage connect to the candidate module.</p>
                        </div>
                    </div>
                </section>
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Create CV online</h2>
                    <div class="mt-4 grid gap-3 text-sm">
                        @foreach (['Personal summary', 'Work experience', 'Education', 'Skills', 'Languages', 'Certificates'] as $field)
                            <div class="rounded border border-slate-200 bg-slate-50 px-4 py-3">{{ $field }}</div>
                        @endforeach
                    </div>
                </section>
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">CV score</h2>
                    <div class="mt-5">
                        <div class="h-3 rounded-full bg-slate-100">
                            <div class="h-3 w-[72%] rounded-full bg-[#2a7190]"></div>
                        </div>
                        <p class="mt-3 text-3xl font-extrabold text-[#2a7190]">72%</p>
                        <p class="text-sm text-slate-600">Missing information alerts appear before download.</p>
                    </div>
                </section>
            </div>

            <div class="mt-8 grid gap-5 lg:grid-cols-2">
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Templates</h2>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach (['Gulf CV format', 'UK CV format', 'Canada resume format', 'Australia resume format', 'US resume format', 'European CV format'] as $template)
                            <div class="rounded border border-slate-200 p-4 font-semibold">{{ $template }}</div>
                        @endforeach
                    </div>
                </section>
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Downloads and letters</h2>
                    <div class="mt-4 grid gap-3">
                        @foreach (['Download CV as PDF', 'ATS-friendly CV format', 'Cover letter builder', 'AI CV improvement suggestions later', 'Missing information alerts'] as $item)
                            <div class="rounded border border-slate-200 bg-slate-50 px-4 py-3 font-semibold">{{ $item }}</div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </section>
</x-layouts.app>
