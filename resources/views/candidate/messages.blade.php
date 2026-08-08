<x-layouts.app title="Messages">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
            <div>
                <p class="font-semibold text-[#2a7190]">Job seeker dashboard</p>
                <h1 class="mt-1 text-3xl font-extrabold">Employer messages</h1>
            </div>

            @if (session('status'))
                <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
            @endif

            <div class="mt-6 grid gap-4">
                @forelse ($messages as $message)
                    <article class="rounded-lg bg-white p-5 shadow-sm">
                        <div class="flex flex-col justify-between gap-3 sm:flex-row">
                            <div>
                                <h2 class="font-bold">{{ $message->employer?->name }}</h2>
                                <p class="mt-1 text-sm text-slate-600">{{ $message->application?->job?->title ?: 'General message' }} · {{ $message->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="text-xs font-semibold text-slate-500">{{ $message->sender_id === auth()->id() ? 'You sent' : 'Employer sent' }}</span>
                        </div>
                        <p class="mt-4 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $message->body }}</p>
                        <form method="POST" action="{{ route('candidate.messages.store') }}" class="mt-4 grid gap-3">
                            @csrf
                            <input type="hidden" name="employer_id" value="{{ $message->employer_id }}">
                            <input type="hidden" name="job_application_id" value="{{ $message->job_application_id }}">
                            <textarea name="body" rows="2" class="w-full rounded border-slate-300 px-4 py-3 text-sm" placeholder="Reply to this employer"></textarea>
                            <button class="w-fit rounded bg-[#2a7190] px-4 py-2 text-sm font-bold text-white">Send reply</button>
                        </form>
                    </article>
                @empty
                    <div class="rounded-lg bg-white p-8 text-center shadow-sm">
                        <p class="font-bold">No messages yet</p>
                        <p class="mt-2 text-sm text-slate-600">Employer replies and interview requests will appear here.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.app>
