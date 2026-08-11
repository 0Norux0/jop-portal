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
            <dl class="mt-4 grid grid-cols-2 gap-x-3 gap-y-2 text-xs">
                @foreach ([
                    'Jobs' => $plan['job_posts'],
                    'Featured' => $plan['featured_jobs'],
                    'Searches' => $plan['candidate_searches'],
                    'CVs' => $plan['cv_credits'],
                    'Contacts' => $plan['contact_credits'],
                    'Matching' => $plan['matching_requests'],
                    'AI tools' => $plan['ai_requests'],
                ] as $label => $value)
                    <div class="rounded border border-slate-100 bg-slate-50 px-2 py-1.5">
                        <dt class="text-[11px] font-semibold leading-tight text-slate-500">{{ $label }}</dt>
                        <dd class="mt-0.5 text-sm font-extrabold leading-tight text-slate-950">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        </article>
    @endforeach
</div>
