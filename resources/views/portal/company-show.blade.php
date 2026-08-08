<x-layouts.app :title="$employer->name">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg bg-white shadow-sm">
                <div class="relative h-56 bg-[#2a7190]">
                    @if ($employer->cover_path)
                        <img src="{{ asset($employer->cover_path) }}" alt="{{ $employer->name }} cover" class="h-full w-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/25 to-white/10"></div>
                </div>
                <div class="relative p-6">
                    <div class="-mt-16 flex flex-col gap-4 sm:flex-row sm:items-end">
                        @if ($employer->logo_path)
                            <img src="{{ asset($employer->logo_path) }}" alt="{{ $employer->name }}" class="h-28 w-28 rounded-lg border-4 border-white bg-white object-contain shadow-sm">
                        @else
                            <div class="flex h-28 w-28 items-center justify-center rounded-lg border-4 border-white bg-[#2a7190] text-4xl font-bold text-white shadow-sm">{{ str($employer->name)->substr(0, 1) }}</div>
                        @endif
                        <div class="pb-2">
                            <h1 class="text-3xl font-extrabold">{{ $employer->name }}</h1>
                            <p class="mt-2 text-slate-600">{{ $employer->industry }} · {{ $employer->city }}, {{ $employer->country }} · {{ $employer->company_size }}</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-xs font-bold text-[#2a7190]">{{ str($employer->verification_status)->headline() }} employer</span>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold">{{ $employer->jobs->count() }} open jobs</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_320px]">
                        <article>
                            <h2 class="text-xl font-bold">About</h2>
                            <p class="mt-3 leading-7 text-slate-700">{{ $employer->description ?: 'This employer has not added a public company description yet.' }}</p>
                            <h2 class="mt-8 text-xl font-bold">Open jobs</h2>
                            <div class="mt-4 grid gap-3">
                                @forelse ($employer->jobs as $job)
                                    <a href="{{ route('jobs.show', $job->slug) }}" class="rounded border border-slate-200 p-4 hover:border-[#2a7190]">
                                        <p class="font-bold">{{ $job->title }}</p>
                                        <p class="mt-1 text-sm text-slate-600">{{ $job->city }}, {{ $job->country }} · {{ str($job->work_mode)->replace('_', '-')->title() }}</p>
                                    </a>
                                @empty
                                    <p class="rounded border border-dashed border-slate-300 p-5 text-sm text-slate-600">No public jobs are open right now.</p>
                                @endforelse
                            </div>
                        </article>
                        <aside class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                            <h2 class="font-bold">Company details</h2>
                            <dl class="mt-4 grid gap-3 text-sm">
                                <div><dt class="text-slate-500">Website</dt><dd class="font-semibold">@if($employer->website_url)<a href="{{ $employer->website_url }}">{{ $employer->website_url }}</a>@else Not listed @endif</dd></div>
                                <div><dt class="text-slate-500">Location</dt><dd class="font-semibold">{{ $employer->city }}, {{ $employer->country }}</dd></div>
                                <div><dt class="text-slate-500">Industry</dt><dd class="font-semibold">{{ $employer->industry ?: 'Not listed' }}</dd></div>
                                <div><dt class="text-slate-500">Company size</dt><dd class="font-semibold">{{ $employer->company_size ?: 'Not listed' }}</dd></div>
                            </dl>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
