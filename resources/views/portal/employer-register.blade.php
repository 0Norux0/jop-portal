@php($pageContent = \App\Support\PageContent::get('employer-register'))
<x-layouts.app :title="$pageContent['title']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-5xl px-6 py-12 lg:px-8">
            <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
            <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $pageContent['title'] }}</h1>
            <p class="mt-4 max-w-3xl leading-7 text-slate-700">{{ $pageContent['description'] }}</p>
            <form class="mt-8 rounded-lg bg-white p-6 shadow-sm">
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach ([
                        'Company name',
                        'Contact person',
                        'Business email',
                        'Mobile / WhatsApp',
                        'City',
                        'Company website',
                        'Industry',
                        'Company size',
                        'Password',
                    ] as $field)
                        <label class="grid gap-1 text-sm font-semibold">
                            {{ $field }}
                            <input type="{{ str_contains($field, 'email') ? 'email' : (str_contains($field, 'Password') ? 'password' : 'text') }}" class="rounded border-slate-300">
                        </label>
                    @endforeach
                    <label class="grid gap-1 text-sm font-semibold">
                        Country
                        <select class="rounded border-slate-300">
                            @foreach ($portal['countries'] as $country)
                                <option>{{ $country }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    @foreach (['Commercial license upload', 'Company registration upload', 'Tax number where applicable', 'Recruitment agency license if agency', 'Official email/domain verification'] as $item)
                        <div class="rounded border border-dashed border-slate-300 bg-slate-50 p-4">
                            <p class="font-semibold">{{ $item }}</p>
                            <p class="mt-1 text-sm text-slate-600">Verification workflow placeholder.</p>
                        </div>
                    @endforeach
                </div>
                <a href="/login" class="mt-6 inline-flex rounded bg-[#2a7190] px-5 py-3 text-sm font-semibold text-white">Submit for review</a>
            </form>
        </div>
    </section>
</x-layouts.app>
