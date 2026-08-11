<section class="rounded-lg bg-white p-5 shadow-sm">
    <h2 class="text-xl font-bold">{{ $title }}</h2>
    <div class="mt-4 grid gap-3">
        @forelse ($items as $item)
            <div class="rounded border border-slate-200 p-4">
                <p class="font-bold">{{ $item->title ?? $item->name ?? $item->school ?? $item->company }}</p>
                <p class="mt-1 text-sm text-slate-600">{{ $item->role ?? $item->issuer ?? $item->degree ?? '' }}</p>
                @if (! empty($item->url))
                    <a href="{{ $item->url }}" class="mt-2 inline-flex text-sm font-bold text-[#2a7190]">Open link</a>
                @endif
                @if (! empty($item->file_path))
                    <a href="{{ asset($item->file_path) }}" class="mt-2 inline-flex text-sm font-bold text-[#2a7190]">Open file</a>
                @endif
                @if (! empty($item->description))
                    <p class="mt-3 text-sm leading-6 text-slate-700">{{ $item->description }}</p>
                @endif
            </div>
        @empty
            <p class="rounded border border-dashed border-slate-300 p-4 text-sm text-slate-600">No {{ strtolower($title) }} listed.</p>
        @endforelse
    </div>
</section>
