@php($pageContent = \App\Support\PageContent::get('blog'))
<x-layouts.app :title="$pageContent['title']">
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
        <h1 class="mt-2 text-3xl font-bold">{{ $pageContent['title'] }}</h1>
        <p class="mt-3 max-w-3xl text-slate-600">{{ $pageContent['description'] }}</p>
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="/career-coach" class="rounded bg-[#2a7190] px-5 py-3 text-sm font-semibold text-white">Open career coach</a>
            <a href="/cv-builder" class="rounded border border-[#2a7190] px-5 py-3 text-sm font-semibold text-[#2a7190]">CV support</a>
        </div>
        <section class="mt-8 rounded-lg border border-slate-200 bg-white p-6">
            <h2 class="text-2xl font-bold">Career resource categories</h2>
            <div class="mt-5 flex flex-wrap gap-2">
                @foreach ($portal['blog_categories'] as $category)
                    <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-sm font-semibold text-[#2a7190]">{{ $category }}</span>
                @endforeach
            </div>
        </section>
        <div class="mt-8 grid gap-4 md:grid-cols-2">
            @foreach ($portal['blog_topics'] as $topic)
                <article class="rounded-lg border border-slate-200 bg-white p-5">
                    <h2 class="font-bold">{{ $topic }}</h2>
                    <p class="mt-2 text-sm text-slate-600">Draft article placeholder ready for CMS/blog implementation.</p>
                </article>
            @endforeach
        </div>
    </section>
</x-layouts.app>
