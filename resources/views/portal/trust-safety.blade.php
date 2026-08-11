@php($pageContent = \App\Support\PageContent::get('trust-safety'))
<x-layouts.app :title="$pageContent['title']">
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
        <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $pageContent['title'] }}</h1>
        <p class="mt-3 max-w-3xl text-slate-600">{{ $pageContent['description'] }}</p>
        @if (session('status'))
            <div class="mt-5 rounded border border-green-300 bg-green-50 p-4 text-sm font-semibold text-green-800">{{ session('status') }}</div>
        @endif
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="/candidate-verification" class="rounded border border-[#2a7190] px-5 py-3 text-sm font-semibold text-[#2a7190]">Candidate verification</a>
            <a href="/employer-verification" class="rounded border border-[#2a7190] px-5 py-3 text-sm font-semibold text-[#2a7190]">Employer verification</a>
            <a href="{{ route('report.create', ['type' => 'employer']) }}" class="rounded border border-red-200 bg-red-50 px-5 py-3 text-sm font-semibold text-red-700">Report employer</a>
            <a href="{{ route('report.create', ['type' => 'candidate']) }}" class="rounded border border-red-200 bg-red-50 px-5 py-3 text-sm font-semibold text-red-700">Report candidate</a>
        </div>
        <div class="mt-8 grid gap-5 md:grid-cols-2">
            @foreach (['Protection tools' => ['Report job', 'Report employer', 'Report candidate', 'Admin review queue', 'Block user', 'Suspicious keyword detection'], 'Overseas hiring rules' => ['No illegal recruitment fee policy', 'No passport surrender requests', 'No suspicious payments', 'Manual approval for overseas agencies', 'Candidate consent before contact sharing', 'Visa outcome disclaimer'], 'Verification levels' => ['Unverified', 'Email verified', 'Phone verified', 'Company document verified', 'Recruitment agency verified', 'Trusted employer', 'Approved employer'], 'Audit and compliance' => ['Audit log', 'Payment record', 'Job posting rules', 'Anti-scam policy', 'Equal opportunity policy', 'Data deletion request']] as $title => $items)
                <div class="rounded-lg border border-slate-200 bg-white p-5">
                    <h2 class="font-bold">{{ $title }}</h2>
                    <ul class="mt-4 grid gap-2 text-sm text-slate-700">
                        @foreach ($items as $item)
                            <li>• {{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </section>
</x-layouts.app>
