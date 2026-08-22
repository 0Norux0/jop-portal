@php($pageContent = \App\Support\PageContent::get('portfolio'))
<x-layouts.app :title="$pageContent['title']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
            <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $pageContent['title'] }}</h1>
            <p class="mt-4 max-w-3xl leading-7 text-slate-700">{{ $pageContent['description'] }}</p>

            <div class="mt-8 grid gap-5 lg:grid-cols-4">
                @foreach (['Images', 'PDFs', 'Videos', 'Website links', 'GitHub links', 'Behance links', 'Google Drive links', 'Certificates'] as $type)
                    <div class="rounded-lg bg-white p-5 shadow-sm">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#2a7190] font-bold text-white">{{ substr($type, 0, 1) }}</div>
                        <h2 class="mt-4 font-bold">{{ $type }}</h2>
                    </div>
                @endforeach
            </div>

            <section class="mt-8 rounded-lg bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold">Smart portfolio source parsing</h2>
                <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">Candidates can attach profile links and project sources that later parsing jobs can normalize into searchable metadata.</p>
                <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ([
                        'GitHub parsing' => 'Repositories, languages, commits, README links',
                        'Behance parsing' => 'Creative projects, images, tools, case studies',
                        'Dribbble parsing' => 'Design shots, tags, UI/product samples',
                        'ArtStation parsing' => '3D, concept art, game, and media assets',
                        'Kaggle parsing' => 'Datasets, notebooks, competitions, data skills',
                        'YouTube parsing' => 'Video demos, intro reels, tutorial proof',
                        'Personal website parsing' => 'Portfolio pages, services, contact-safe links',
                        'Research paper links' => 'Publications, abstracts, DOI or reference URLs',
                    ] as $title => $description)
                        <div class="rounded border border-slate-200 bg-slate-50 p-4">
                            <p class="text-sm font-bold">{{ $title }}</p>
                            <p class="mt-1 text-xs leading-5 text-slate-600">{{ $description }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <div class="mt-8 grid gap-5 lg:grid-cols-3">
                @foreach (['Before/after work samples', 'Case studies', 'Course assignments', 'Project descriptions'] as $item)
                    <section class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold">{{ $item }}</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-600">Structured portfolio blocks make candidate work searchable and easier for employers to compare.</p>
                    </section>
                @endforeach
            </div>

            <section class="mt-8 rounded-lg bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold">Useful for many careers</h2>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach (['Graphic designers', 'Web designers', 'Developers', 'Video editors', 'Architectural designers', 'AutoCAD/Revit/SketchUp users', 'AI/data science candidates', 'Cybersecurity learners', 'Caregivers', 'Office admins', 'Teachers/trainers', 'Hospitality workers'] as $career)
                        <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-sm font-semibold text-[#2a7190]">{{ $career }}</span>
                    @endforeach
                </div>
            </section>

            <section class="mt-8 rounded-lg bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold">Searchable project metadata</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                    @foreach (['Project title', 'Role', 'Tools used', 'Industry', 'Skills proved', 'Completion date', 'Source URL', 'Media type', 'Verification status', 'Reviewer notes'] as $field)
                        <div class="rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold">{{ $field }}</div>
                    @endforeach
                </div>
            </section>
        </div>
    </section>
</x-layouts.app>
