@php
    $billing = \App\Support\EmployerPortalContent::load()['billing'];
@endphp

<x-layouts.app :title="$billing['eyebrow']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-10 sm:px-6 lg:grid-cols-[280px_1fr] lg:px-8">
            @include('employer.partials.nav')
            <div class="space-y-6">
                @if (session('status'))
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
                @endif
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <p class="font-semibold text-[#2a7190]">{{ $billing['eyebrow'] }}</p>
                    <h1 class="mt-1 text-3xl font-extrabold">{{ $billing['title'] }}</h1>
                    <form method="POST" action="{{ route('business.billing.update') }}" class="mt-6 grid gap-5 sm:grid-cols-2">
                        @csrf
                        <div>
                            <label class="text-sm font-bold">{{ $billing['owner_label'] }}</label>
                            <input value="{{ $employer->contact_name }}" disabled class="mt-2 w-full rounded border-slate-300 bg-slate-50 px-4 py-3">
                        </div>
                        <div>
                            <label class="text-sm font-bold">{{ $billing['email_label'] }}</label>
                            <input name="billing_email" type="email" value="{{ old('billing_email', $employer->billing_email ?: $employer->contact_email) }}" required class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                        </div>
                        <div>
                            <label class="text-sm font-bold">{{ $billing['plan_label'] }}</label>
                            <input value="{{ $plans[$employer->billing_plan]['label'] ?? str($employer->billing_plan)->headline() }}" disabled class="mt-2 w-full rounded border-slate-300 bg-slate-50 px-4 py-3">
                            <p class="mt-2 text-xs text-slate-500">Plan changes must be requested from Paid Services and approved by the platform.</p>
                        </div>
                        <div>
                            <label class="text-sm font-bold">{{ $billing['status_label'] }}</label>
                            <input value="{{ str($employer->premium_status)->headline() }}" disabled class="mt-2 w-full rounded border-slate-300 bg-slate-50 px-4 py-3">
                            @if (filled($employer->notes['requested_plan_label'] ?? null))
                                <p class="mt-2 text-xs text-slate-500">Requested plan: {{ $employer->notes['requested_plan_label'] }}</p>
                            @endif
                        </div>
                        <div class="sm:col-span-2">
                            <div class="flex flex-wrap gap-3">
                                <button class="rounded bg-[#2a7190] px-5 py-3 font-bold text-white shadow-sm transition hover:bg-[#215d76]">{{ $billing['save_label'] }}</button>
                                <a href="{{ route('business.services') }}" class="inline-flex rounded border border-[#2a7190] bg-white px-5 py-3 font-bold text-[#2a7190] shadow-sm transition hover:bg-[#e9f3f7]">Request plan change</a>
                            </div>
                        </div>
                    </form>
                </section>
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <div class="flex flex-col justify-between gap-3 md:flex-row md:items-center">
                        <div>
                            <p class="font-semibold text-[#2a7190]">Subscription entitlements</p>
                            <h2 class="mt-1 text-2xl font-extrabold">Plan overview</h2>
                            <p class="mt-2 text-sm text-slate-600">These cards are read-only. Selecting a different plan is handled through a request and platform approval.</p>
                        </div>
                        <a href="{{ route('business.services') }}" class="inline-flex rounded border border-[#2a7190] bg-white px-4 py-2 text-sm font-bold text-[#2a7190] shadow-sm transition hover:bg-[#e9f3f7]">Request services</a>
                    </div>
                    <div class="mt-5">
                        @include('employer.partials.plan-cards', ['plans' => $plans, 'employer' => $employer])
                    </div>
                </section>
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Current credit balance</h2>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                        @foreach ([
                            'Published job limit' => $employer->job_post_limit,
                            'Featured job credits' => $employer->featured_job_credits,
                            'Candidate search credits' => $employer->candidate_search_credits,
                            'CV access credits' => $employer->cv_access_credits,
                            'Contact credits' => $employer->candidate_contact_credits,
                            'Matching credits' => $employer->matching_request_credits,
                            'AI recruitment credits' => $employer->ai_recruitment_credits,
                        ] as $label => $value)
                            <div class="rounded border border-slate-200 p-4">
                                <p class="text-2xl font-extrabold text-[#2a7190]">{{ $value }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
                @if ($transactions->isNotEmpty())
                    <section class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold">Credit usage</h2>
                        <div class="mt-4 overflow-hidden rounded border border-slate-200">
                            @foreach ($transactions as $transaction)
                                <div class="grid gap-2 border-b border-slate-100 p-4 text-sm last:border-b-0 md:grid-cols-[1fr_160px_120px]">
                                    <p class="font-semibold">{{ $transaction->description }}</p>
                                    <p class="text-slate-600">{{ str($transaction->credit_type)->replace('_', ' ')->headline() }}</p>
                                    <p class="font-bold text-[#2a7190]">{{ $transaction->amount }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
                <div class="grid gap-4 md:grid-cols-3">
                    @foreach ([
                        $billing['invoices_title'] => $billing['invoices_copy'],
                        $billing['payment_title'] => $billing['payment_copy'],
                        $billing['team_title'] => $billing['team_copy'],
                    ] as $title => $copy)
                        <section class="rounded-lg bg-white p-5 shadow-sm">
                            <h2 class="font-bold">{{ $title }}</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $copy }}</p>
                        </section>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
