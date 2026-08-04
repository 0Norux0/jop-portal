<x-layouts.app :title="$title">
    <section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <p class="font-semibold text-[#2a7190]">Legal and compliance</p>
        <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $title }}</h1>
        <div class="mt-6 rounded-lg border border-slate-200 bg-white p-6 leading-7 text-slate-700">
            <p>This page is part of the international compliance foundation for the portal. Final legal text should be reviewed by a qualified professional before production launch.</p>
            <p class="mt-4 font-semibold">Important disclaimer</p>
            <p class="mt-2">The portal does not guarantee visa approval, job placement, salary, migration outcome, employer selection, or interview success. It only connects job seekers and employers.</p>
            <p class="mt-4">Users must not request or pay illegal placement fees, surrender passports, share sensitive documents outside verified workflows, or bypass platform safety rules.</p>
            @if ($slug === 'gdpr-consent')
                <p class="mt-4">European users should be able to review consent, request data access, request correction, and request deletion through controlled workflows.</p>
            @endif
            @if ($slug === 'equal-opportunity')
                <p class="mt-4">Employers should follow equal-opportunity principles and use nationality or gender requirements only when legally acceptable.</p>
            @endif
        </div>
    </section>
</x-layouts.app>
