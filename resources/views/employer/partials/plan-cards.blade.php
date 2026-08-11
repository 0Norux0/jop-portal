@props(['plans', 'employer'])

<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach ($plans as $key => $plan)
        @php($active = $employer->billing_plan === $key)
        <article @class([
            'relative flex aspect-square min-h-0 flex-col rounded-lg border bg-white p-5 shadow-sm transition',
            'border-[#2a7190] ring-2 ring-[#2a7190]/10' => $active,
            'border-slate-200' => ! $active,
        ])>
            @if ($active)
                <span class="absolute right-4 top-4 rounded-full bg-[#2a7190] px-3 py-1 text-xs font-bold text-white">Active</span>
            @endif
            <div class="pr-20">
                <h3 class="text-lg font-extrabold leading-snug text-slate-950">{{ $plan['label'] }}</h3>
            </div>
            <dl class="mt-5 grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                @foreach ([
                    'Job posts' => $plan['job_posts'],
                    'Featured jobs' => $plan['featured_jobs'],
                    'Searches' => $plan['candidate_searches'],
                    'CV credits' => $plan['cv_credits'],
                    'Contact credits' => $plan['contact_credits'],
                    'Matching' => $plan['matching_requests'],
                    'AI tools' => $plan['ai_requests'],
                ] as $label => $value)
                    <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-2">
                        <dt class="truncate text-slate-600">{{ $label }}</dt>
                        <dd class="shrink-0 font-extrabold text-slate-950">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        </article>
    @endforeach
</div>
