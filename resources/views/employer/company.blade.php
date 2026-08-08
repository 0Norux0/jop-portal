<x-layouts.app title="Company Page">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-10 sm:px-6 lg:grid-cols-[280px_1fr] lg:px-8">
            @include('employer.partials.nav')
            <div>
                @if (session('status'))
                    <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
                @endif
                <form method="POST" action="{{ route('business.company.update') }}" enctype="multipart/form-data" class="rounded-lg bg-white p-6 shadow-sm">
                    @csrf
                    @include('auth._errors')
                    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                        <div>
                            <p class="font-semibold text-[#2a7190]">Create a Company Page</p>
                            <h1 class="mt-1 text-3xl font-extrabold">Company identity</h1>
                        </div>
                        <label class="flex items-center gap-2 rounded border border-slate-200 px-4 py-2 text-sm font-bold">
                            <input type="checkbox" name="is_published" value="1" @checked($employer->is_published)>
                            Publish page
                        </label>
                    </div>

                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-bold">Company name</label>
                            <input name="name" value="{{ old('name', $employer->name) }}" required class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                        </div>
                        <div>
                            <label class="text-sm font-bold">Industry</label>
                            <input name="industry" value="{{ old('industry', $employer->industry) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                        </div>
                        <div>
                            <label class="text-sm font-bold">Company size</label>
                            <select name="company_size" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                                <option value="">Select size</option>
                                @foreach (['1-10 employees', '11-50 employees', '51-200 employees', '201-500 employees', '501-1000 employees', '1000+ employees'] as $size)
                                    <option value="{{ $size }}" @selected(old('company_size', $employer->company_size) === $size)>{{ $size }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-bold">Website URL</label>
                            <input name="website_url" type="url" value="{{ old('website_url', $employer->website_url) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="https://">
                        </div>
                        <div>
                            <label class="text-sm font-bold">Country</label>
                            <input name="country" value="{{ old('country', $employer->country) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                        </div>
                        <div>
                            <label class="text-sm font-bold">City</label>
                            <input name="city" value="{{ old('city', $employer->city) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                        </div>
                        <div>
                            <label class="text-sm font-bold">Logo</label>
                            <input name="logo" type="file" accept="image/*" class="mt-2 w-full rounded border border-slate-300 px-4 py-3">
                        </div>
                        <div>
                            <label class="text-sm font-bold">Cover image</label>
                            <input name="cover" type="file" accept="image/*" class="mt-2 w-full rounded border border-slate-300 px-4 py-3">
                        </div>
                        <div>
                            <label class="text-sm font-bold">Contact name</label>
                            <input name="contact_name" value="{{ old('contact_name', $employer->contact_name) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                        </div>
                        <div>
                            <label class="text-sm font-bold">Contact email</label>
                            <input name="contact_email" type="email" value="{{ old('contact_email', $employer->contact_email) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                        </div>
                        <div>
                            <label class="text-sm font-bold">Contact phone</label>
                            <input name="contact_phone" value="{{ old('contact_phone', $employer->contact_phone) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                        </div>
                        <div>
                            <label class="text-sm font-bold">LinkedIn</label>
                            <input name="linkedin_url" type="url" value="{{ old('linkedin_url', $employer->social_links['linkedin'] ?? '') }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="https://">
                        </div>
                        <div>
                            <label class="text-sm font-bold">Facebook</label>
                            <input name="facebook_url" type="url" value="{{ old('facebook_url', $employer->social_links['facebook'] ?? '') }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="https://">
                        </div>
                        <div>
                            <label class="text-sm font-bold">X / Twitter</label>
                            <input name="x_url" type="url" value="{{ old('x_url', $employer->social_links['x'] ?? '') }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="https://">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-sm font-bold">About company</label>
                            <textarea name="description" rows="7" class="mt-2 w-full rounded border-slate-300 px-4 py-3">{{ old('description', $employer->description) }}</textarea>
                        </div>
                    </div>
                    <button class="mt-6 rounded bg-[#2a7190] px-5 py-3 font-bold text-white">Save company page</button>
                    @if ($employer->is_published)
                        <a href="{{ route('companies.show', $employer->slug) }}" class="ml-3 inline-flex rounded border border-slate-300 px-5 py-3 font-bold">View public page</a>
                    @endif
                </form>
            </div>
        </div>
    </section>
</x-layouts.app>
