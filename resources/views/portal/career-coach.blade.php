@php($pageContent = \App\Support\PageContent::get('career-coach'))
<x-layouts.app :title="$pageContent['title']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
            <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $pageContent['title'] }}</h1>
            <p class="mt-4 max-w-3xl leading-7 text-slate-700">{{ $pageContent['description'] }}</p>

            <div class="mt-8 grid gap-6 lg:grid-cols-[420px_1fr]">
                <form method="GET" action="/career-coach" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <label class="block text-sm font-semibold" for="target_role">Target role</label>
                    <input id="target_role" name="target_role" type="text" value="{{ $input['target_role'] ?? '' }}" required class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="Caregiver, Laravel developer, accountant...">

                    <label class="mt-5 block text-sm font-semibold" for="skills">Current skills</label>
                    <textarea id="skills" name="skills" rows="4" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="Excel, bookkeeping, English...">{{ $input['skills'] ?? '' }}</textarea>

                    <div class="mt-5 grid gap-3 text-sm">
                        @foreach (['has_cv' => 'I have a CV', 'has_portfolio' => 'I have a portfolio/project sample', 'has_video' => 'I have a video introduction'] as $name => $label)
                            <label class="flex items-center gap-2 rounded border border-slate-200 bg-slate-50 px-4 py-3">
                                <input type="checkbox" name="{{ $name }}" value="1" @checked(! empty($input[$name]))>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    <button type="submit" class="mt-6 w-full rounded bg-[#2a7190] px-5 py-3 text-sm font-semibold text-white">Generate plan</button>
                </form>

                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    @if ($advice)
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="font-semibold text-[#2a7190]">Rule-based job-fit estimate</p>
                                <h2 class="mt-1 text-3xl font-extrabold">{{ $advice['fit_score'] }}%</h2>
                            </div>
                            <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-sm font-bold text-[#2a7190]">No AI provider required</span>
                        </div>

                        <div class="mt-6 grid gap-5 lg:grid-cols-2">
                            <section>
                                <h3 class="font-bold">Next steps</h3>
                                <ul class="mt-3 grid gap-2 text-sm text-slate-700">
                                    @foreach ($advice['next_steps'] as $item)
                                        <li>• {{ $item }}</li>
                                    @endforeach
                                </ul>
                            </section>
                            <section>
                                <h3 class="font-bold">Skill improvement suggestions</h3>
                                <ul class="mt-3 grid gap-2 text-sm text-slate-700">
                                    @forelse ($advice['skill_gaps'] as $item)
                                        <li>• Add or prove {{ $item }}</li>
                                    @empty
                                        <li>• Your listed skills cover the main starter areas for this role.</li>
                                    @endforelse
                                </ul>
                            </section>
                        </div>

                        <section class="mt-6 rounded-lg bg-slate-50 p-5">
                            <h3 class="font-bold">Interview practice questions</h3>
                            <div class="mt-3 grid gap-3 text-sm text-slate-700">
                                @foreach ($advice['interview_questions'] as $question)
                                    <p class="rounded border border-slate-200 bg-white px-4 py-3">{{ $question }}</p>
                                @endforeach
                            </div>
                        </section>
                    @else
                        <h2 class="text-2xl font-bold">Build a quick career plan</h2>
                        <p class="mt-3 leading-7 text-slate-600">Enter a target role and your current skills to get a rule-based fit estimate, skill gaps, portfolio suggestions, and interview practice questions.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
