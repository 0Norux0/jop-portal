@props(['plans', 'employer'])

<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach ($plans as $key => $plan)
        @php($active = $employer->billing_plan === $key)
        @php($displayName = ['free' => 'Free', 'growth' => 'Growth', 'premium' => 'Premium', 'enterprise' => 'Enterprise'][$key] ?? $plan['label'])
        <article @class([
            'relative flex aspect-square min-h-0 flex-col overflow-hidden rounded-lg border bg-white p-4 shadow-sm transition',
            'border-[#2a7190] ring-2 ring-[#2a7190]/10' => $active,
            'border-slate-200' => ! $active,
        ])>
            @if ($active)
                <span class="absolute right-3 top-3 rounded-full bg-[#2a7190] px-2.5 py-1 text-[11px] font-bold leading-none text-white">Active</span>
            @endif
            <div class="pr-16">
                <h3 class="text-lg font-extrabold leading-tight text-slate-950">{{ $displayName }}</h3>
                <p class="mt-1 text-[11px] font-semibold leading-tight text-slate-500">{{ $plan['label'] }}</p>
            </div>
            <dl class="mt-3 grid flex-1 grid-cols-2 gap-1.5 text-xs">
                @foreach ([
                    'Jobs' => $plan['job_posts'],
                    'Featured' => $plan['featured_jobs'],
                    'Searches' => $plan['candidate_searches'],
                    'CVs' => $plan['cv_credits'],
                    'Contacts' => $plan['contact_credits'],
                    'Matching' => $plan['matching_requests'],
                    'AI tools' => $plan['ai_requests'],
                ] as $label => $value)
                    <div class="flex min-w-0 items-center justify-between gap-1 rounded border border-slate-100 bg-slate-50 px-2 py-1">
                        <dt class="truncate text-[10px] font-semibold leading-none text-slate-500">{{ $label }}</dt>
                        <dd class="shrink-0 text-xs font-extrabold leading-none text-slate-950">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        </article>
    @endforeach
</div>
