@php
    $pageContent = \App\Support\PageContent::get('trust-safety');
    $typeLabel = ucfirst($type);
@endphp
<x-layouts.app :title="'Report '.$typeLabel">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
            <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
            <h1 class="mt-2 text-3xl font-extrabold text-[#2a7190]">Report {{ strtolower($typeLabel) }}</h1>
            <p class="mt-3 text-sm leading-6 text-slate-600">Reports are queued for admin review and checked for illegal fees, passport surrender requests, suspicious payments, and unsafe overseas hiring claims.</p>

            @include('auth._errors')

            <form method="POST" action="/report" class="mt-8 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <div>
                    <label class="block text-sm font-semibold" for="subject">Reported {{ strtolower($typeLabel) }}</label>
                    <input id="subject" name="subject" type="text" required value="{{ old('subject', $subject) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                </div>
                <div class="mt-5">
                    <label class="block text-sm font-semibold" for="reason">Reason</label>
                    <select id="reason" name="reason" required class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                        @foreach ($reasons as $reason)
                            <option value="{{ $reason }}" @selected(old('reason') === $reason)>{{ $reason }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-5">
                    <label class="block text-sm font-semibold" for="description">What happened?</label>
                    <textarea id="description" name="description" rows="6" required class="mt-2 w-full rounded border-slate-300 px-4 py-3">{{ old('description') }}</textarea>
                </div>
                <div class="mt-5">
                    <label class="block text-sm font-semibold" for="contact_email">Your email, optional</label>
                    <input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email') }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                </div>
                <div class="mt-6 flex flex-wrap gap-3">
                    <button type="submit" class="rounded bg-[#2a7190] px-5 py-3 text-sm font-semibold text-white">Submit report</button>
                    <a href="/trust-safety" class="rounded border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700">Cancel</a>
                </div>
            </form>
        </div>
    </section>
</x-layouts.app>
