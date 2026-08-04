@php($pageContent = \App\Support\PageContent::get('video-profile'))
<x-layouts.app :title="$pageContent['title']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
            <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $pageContent['title'] }}</h1>
            <p class="mt-4 max-w-3xl leading-7 text-slate-700">{{ $pageContent['description'] }}</p>

            <div class="mt-8 grid gap-5 lg:grid-cols-[1fr_360px]">
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Upload options</h2>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        @foreach (['30-second intro', '60-second intro', '90-second intro', 'Professional video CV'] as $option)
                            <div class="rounded-lg border border-slate-200 p-5">
                                <div class="flex aspect-video items-center justify-center rounded bg-[#e9f3f7] text-[#2a7190]">
                                    <svg class="h-12 w-12" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M8 5v14l11-7L8 5Z" fill="currentColor"/>
                                    </svg>
                                </div>
                                <p class="mt-3 font-semibold">{{ $option }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
                <aside class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Controls</h2>
                    <div class="mt-5 grid gap-3 text-sm">
                        @foreach (['Admin approval', 'File size control', 'Privacy setting', 'Employer-only viewing', 'Public/private option', 'Thumbnail'] as $item)
                            <div class="rounded border border-slate-200 bg-slate-50 px-4 py-3 font-semibold">{{ $item }}</div>
                        @endforeach
                    </div>
                </aside>
            </div>

            <section class="mt-8 rounded-lg bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold">Suggested script guidance</h2>
                <p class="mt-4 rounded bg-slate-50 p-5 leading-7 text-slate-700">My name is ____. I am from ____. I have experience in ____. I completed training/certification in ____. I am looking for a job as ____. I am available for ____. Thank you.</p>
            </section>
        </div>
    </section>
</x-layouts.app>
