<x-layouts.app>
    <div class="mx-auto max-w-3xl px-4 py-10">
        <h1 class="text-2xl font-extrabold text-slate-950">Email previews</h1>
        <p class="mt-2 text-sm text-slate-600">Preview the exact email templates sent by ICSA Jobs.</p>

        <div class="mt-6 grid gap-3">
            @foreach ($previews as $preview)
                <a href="{{ $preview['url'] }}" class="rounded-lg border border-slate-200 bg-white px-5 py-4 font-semibold text-[#2a7190] hover:bg-slate-50">
                    {{ $preview['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</x-layouts.app>
