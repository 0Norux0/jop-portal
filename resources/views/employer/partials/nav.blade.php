@php
    $items = \App\Support\EmployerPortalContent::navigationItems();
@endphp

<aside class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
    <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
        @if ($employer->logo_path)
            <img src="{{ asset($employer->logo_path) }}" alt="{{ $employer->name }}" class="h-12 w-12 rounded-full border border-slate-200 object-contain">
        @else
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#2a7190] font-bold text-white">{{ str($employer->name)->substr(0, 1) }}</div>
        @endif
        <div class="min-w-0">
            <p class="truncate font-bold">{{ $employer->name }}</p>
            <p class="text-xs font-semibold uppercase text-[#2a7190]">Employer account</p>
        </div>
    </div>
    <nav class="mt-4 grid gap-1 text-sm font-semibold">
        @foreach ($items as $item)
            <a href="{{ route($item['route']) }}" @class([
                'rounded px-3 py-2',
                'bg-[#e9f3f7] text-[#2a7190]' => request()->routeIs($item['route']),
                'text-slate-600 hover:bg-slate-50 hover:text-slate-950' => ! request()->routeIs($item['route']),
            ])>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>
</aside>
