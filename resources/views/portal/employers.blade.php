@php($pageContent = \App\Support\PageContent::get('employers'))
<x-layouts.app :title="$pageContent['title']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
            <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $pageContent['title'] }}</h1>
            <p class="mt-4 max-w-3xl leading-7 text-slate-700">{{ $pageContent['description'] }}</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="/employer-register" class="rounded bg-[#2a7190] px-5 py-3 text-sm font-semibold text-white">Register employer</a>
                <a href="/employer-dashboard" class="rounded border border-[#2a7190] px-5 py-3 text-sm font-semibold text-[#2a7190]">View dashboard</a>
                <a href="/candidate-search" class="rounded border border-[#2a7190] px-5 py-3 text-sm font-semibold text-[#2a7190]">Search candidates</a>
                <a href="/packages" class="rounded border border-[#2a7190] px-5 py-3 text-sm font-semibold text-[#2a7190]">Packages</a>
            </div>
            <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach (['Employer registration' => ['Company name', 'Contact person', 'Business email', 'Mobile/WhatsApp', 'Country/city', 'Company website', 'Industry', 'Company size', 'Verification documents'], 'Employer dashboard' => ['Post jobs', 'Manage jobs', 'View applicants', 'Shortlist candidates', 'Download CVs', 'Watch videos', 'View portfolios', 'Message candidates', 'Analytics'], 'Candidate search' => ['Country', 'Skills', 'Experience', 'Education', 'Certification', 'Salary expectation', 'Video available', 'Portfolio available', 'ICSA/NAS verified optional filter']] as $title => $items)
                    <div class="rounded-lg bg-white p-5 shadow-sm">
                        <h2 class="font-bold">{{ $title }}</h2>
                        <ul class="mt-4 grid gap-2 text-sm text-slate-700">
                            @foreach ($items as $item)
                                <li>• {{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
            <section class="mt-10 rounded-lg bg-white p-6 shadow-sm">
                <h2 class="text-2xl font-bold">Employer groups supported</h2>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">These employer groups are editable from the Portal Content admin area.</p>
                <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($portal['employer_types'] as $type)
                        <div class="rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold">{{ $type }}</div>
                    @endforeach
                </div>
            </section>
        </div>
    </section>
</x-layouts.app>
