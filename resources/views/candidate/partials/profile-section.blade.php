<section class="rounded-lg bg-white p-5 shadow-sm">
    <h2 class="text-xl font-bold">{{ $title }}</h2>
    <div class="mt-4 grid gap-3">
        @forelse ($items as $item)
            <div class="rounded border border-slate-200 p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-bold">{{ $item->title ?? $item->name ?? $item->school ?? $item->company }}</p>
                        <p class="mt-1 text-sm text-slate-600">
                            {{ $item->role ?? $item->issuer ?? $item->degree ?? '' }}
                            {{ isset($item->field) && $item->field ? ' · '.$item->field : '' }}
                            {{ isset($item->location) && $item->location ? ' · '.$item->location : '' }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('candidate.profile.items.destroy', [$type, $item->public_id]) }}">
                        @csrf
                        @method('DELETE')
                        <button class="text-xs font-bold text-red-700">Remove</button>
                    </form>
                </div>
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
            <p class="rounded border border-dashed border-slate-300 p-4 text-sm text-slate-600">No {{ strtolower($title) }} added yet.</p>
        @endforelse
    </div>
</section>
