@php
    $links = $candidate->external_links ?? [];
    $skillsText = implode(', ', $candidate->skills ?? []);
@endphp

<x-layouts.app title="My Profile">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
            @endif

            <div class="grid items-start gap-6 lg:grid-cols-[320px_1fr]">
                <aside class="rounded-lg bg-white p-6 shadow-sm">
                    <p class="font-semibold text-[#2a7190]">Job seeker profile</p>
                    <h1 class="mt-1 text-3xl font-extrabold">{{ $candidate->full_name }}</h1>
                    <p class="mt-2 text-sm text-slate-600">{{ $candidate->headline ?: 'Add a headline to improve your profile.' }}</p>
                    <div class="mt-5 h-3 rounded-full bg-slate-100">
                        <div class="h-3 rounded-full bg-[#2a7190]" style="width: {{ $completion['percent'] }}%"></div>
                    </div>
                    <p class="mt-2 text-sm font-bold text-[#2a7190]">{{ $completion['percent'] }}% complete</p>
                    <div class="mt-5 grid gap-2 text-sm">
                        @forelse ($completion['missing'] as $item)
                            <span class="rounded border border-slate-200 bg-slate-50 px-3 py-2">{{ $item }}</span>
                        @empty
                            <span class="rounded border border-emerald-200 bg-emerald-50 px-3 py-2 text-emerald-800">Profile complete</span>
                        @endforelse
                    </div>
                    @if ($candidate->is_public && $candidate->slug)
                        <a href="{{ route('candidate.public', $candidate->slug) }}" class="mt-5 inline-flex rounded border border-[#2a7190] px-4 py-2 text-sm font-bold text-[#2a7190]">View public profile</a>
                    @endif
                </aside>

                <div class="space-y-6">
                    <form method="POST" action="{{ route('candidate.profile.update') }}" enctype="multipart/form-data" class="rounded-lg bg-white p-6 shadow-sm">
                        @csrf
                        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-end">
                            <div>
                                <p class="font-semibold text-[#2a7190]">Profile basics</p>
                                <h2 class="mt-1 text-2xl font-extrabold">Edit professional profile</h2>
                            </div>
                            <label class="flex items-center gap-2 rounded border border-slate-200 px-4 py-2 text-sm font-semibold">
                                <input type="checkbox" name="is_public" value="1" @checked($candidate->is_public)>
                                Public profile
                            </label>
                        </div>

                        <div class="mt-6 grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-sm font-bold">Full name</label>
                                <input name="full_name" value="{{ old('full_name', $candidate->full_name) }}" required class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                            </div>
                            <div>
                                <label class="text-sm font-bold">Headline</label>
                                <input name="headline" value="{{ old('headline', $candidate->headline) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="Caregiver open to UK roles">
                            </div>
                            <div>
                                <label class="text-sm font-bold">Email</label>
                                <input name="email" type="email" value="{{ old('email', $candidate->email) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                            </div>
                            <div>
                                <label class="text-sm font-bold">Phone</label>
                                <input name="phone" value="{{ old('phone', $candidate->phone) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                            </div>
                            <div>
                                <label class="text-sm font-bold">Country</label>
                                <select name="country" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                                    <option value="">Select country</option>
                                    @foreach ($portal['countries'] as $country)
                                        <option value="{{ $country }}" @selected(old('country', $candidate->country) === $country)>{{ $country }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-bold">City</label>
                                <input name="city" value="{{ old('city', $candidate->city) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                            </div>
                            <div>
                                <label class="text-sm font-bold">Current job title/status</label>
                                <input name="current_job_title" value="{{ old('current_job_title', $candidate->current_job_title) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                            </div>
                            <div>
                                <label class="text-sm font-bold">Preferred category</label>
                                <select name="preferred_job_category" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                                    <option value="">Select category</option>
                                    @foreach ($portal['categories'] as $category)
                                        <option value="{{ $category }}" @selected(old('preferred_job_category', $candidate->preferred_job_category) === $category)>{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-bold">Expected salary</label>
                                <input name="expected_salary" value="{{ old('expected_salary', $candidate->expected_salary) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="GBP 2,200 monthly">
                            </div>
                            <div>
                                <label class="text-sm font-bold">Notice period</label>
                                <input name="notice_period" value="{{ old('notice_period', $candidate->notice_period) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="Available immediately">
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-bold">Bio</label>
                                <textarea name="bio" rows="4" class="mt-2 w-full rounded border-slate-300 px-4 py-3">{{ old('bio', $candidate->bio) }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-bold">Skills</label>
                                <input name="skills" value="{{ old('skills', $skillsText) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="Caregiving, First aid, English">
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-sm font-bold">Upload CV / resume</label>
                                <input name="cv" type="file" accept=".pdf,.doc,.docx" class="mt-2 w-full rounded border border-slate-300 px-4 py-3">
                                @if ($candidate->cv_path)
                                    <a href="{{ asset($candidate->cv_path) }}" class="mt-2 inline-flex text-sm font-bold text-[#2a7190]">Open current CV</a>
                                @endif
                            </div>
                            <div>
                                <label class="text-sm font-bold">Upload video profile</label>
                                <input name="video" type="file" accept="video/mp4,video/webm,video/quicktime" class="mt-2 w-full rounded border border-slate-300 px-4 py-3">
                                @if ($candidate->video_path)
                                    <a href="{{ asset($candidate->video_path) }}" class="mt-2 inline-flex text-sm font-bold text-[#2a7190]">Open current video</a>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 md:grid-cols-2">
                            @foreach ([
                                'linkedin_url' => ['LinkedIn', $candidate->linkedin_url],
                                'portfolio_url' => ['Portfolio URL', $candidate->portfolio_url],
                                'personal_website_url' => ['Personal website', $links['personal_website'] ?? ''],
                                'github_url' => ['GitHub', $links['github'] ?? ''],
                                'behance_url' => ['Behance', $links['behance'] ?? ''],
                                'youtube_url' => ['YouTube', $links['youtube'] ?? ''],
                            ] as $name => [$label, $value])
                                <div>
                                    <label class="text-sm font-bold">{{ $label }}</label>
                                    <input name="{{ $name }}" type="url" value="{{ old($name, $value) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="https://">
                                </div>
                            @endforeach
                        </div>

                        <button class="mt-6 rounded bg-[#2a7190] px-5 py-3 font-bold text-white">Save profile</button>
                    </form>

                    <div class="grid gap-6 xl:grid-cols-2">
                        @include('candidate.partials.profile-section', ['title' => 'Experience', 'items' => $candidate->experiences, 'type' => 'experience'])
                        @include('candidate.partials.profile-section', ['title' => 'Education', 'items' => $candidate->educations, 'type' => 'education'])
                        @include('candidate.partials.profile-section', ['title' => 'Certificates', 'items' => $candidate->certificates, 'type' => 'certificate'])
                        @include('candidate.partials.profile-section', ['title' => 'Projects', 'items' => $candidate->projects, 'type' => 'project'])
                        @include('candidate.partials.profile-section', ['title' => 'Portfolio', 'items' => $candidate->portfolioItems, 'type' => 'portfolio'])
                    </div>

                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold">Add records</h2>
                        <div class="mt-5 grid gap-4 lg:grid-cols-2">
                            <form method="POST" action="{{ route('candidate.profile.experience.store') }}" class="grid gap-3 rounded border border-slate-200 p-4">
                                @csrf
                                <p class="font-bold">Experience</p>
                                <input name="title" required class="rounded border-slate-300 px-4 py-3" placeholder="Job title">
                                <input name="company" required class="rounded border-slate-300 px-4 py-3" placeholder="Company">
                                <input name="location" class="rounded border-slate-300 px-4 py-3" placeholder="Location">
                                <div class="grid gap-3 sm:grid-cols-2"><input name="started_on" type="date" class="rounded border-slate-300 px-4 py-3"><input name="ended_on" type="date" class="rounded border-slate-300 px-4 py-3"></div>
                                <label class="flex items-center gap-2 text-sm"><input name="is_current" type="checkbox" value="1"> Current role</label>
                                <textarea name="description" rows="3" class="rounded border-slate-300 px-4 py-3" placeholder="Responsibilities and achievements"></textarea>
                                <button class="rounded bg-[#2a7190] px-4 py-2 font-bold text-white">Add experience</button>
                            </form>

                            <form method="POST" action="{{ route('candidate.profile.education.store') }}" class="grid gap-3 rounded border border-slate-200 p-4">
                                @csrf
                                <p class="font-bold">Education</p>
                                <input name="school" required class="rounded border-slate-300 px-4 py-3" placeholder="School">
                                <input name="degree" class="rounded border-slate-300 px-4 py-3" placeholder="Degree">
                                <input name="field" class="rounded border-slate-300 px-4 py-3" placeholder="Field of study">
                                <div class="grid gap-3 sm:grid-cols-2"><input name="started_on" type="date" class="rounded border-slate-300 px-4 py-3"><input name="ended_on" type="date" class="rounded border-slate-300 px-4 py-3"></div>
                                <textarea name="description" rows="3" class="rounded border-slate-300 px-4 py-3" placeholder="Notes"></textarea>
                                <button class="rounded bg-[#2a7190] px-4 py-2 font-bold text-white">Add education</button>
                            </form>

                            <form method="POST" action="{{ route('candidate.profile.certificates.store') }}" enctype="multipart/form-data" class="grid gap-3 rounded border border-slate-200 p-4">
                                @csrf
                                <p class="font-bold">Certificate</p>
                                <input name="name" required class="rounded border-slate-300 px-4 py-3" placeholder="Certificate name">
                                <input name="issuer" class="rounded border-slate-300 px-4 py-3" placeholder="Issuer">
                                <input name="credential_number" class="rounded border-slate-300 px-4 py-3" placeholder="Credential number">
                                <input name="credential_url" type="url" class="rounded border-slate-300 px-4 py-3" placeholder="Credential URL">
                                <div class="grid gap-3 sm:grid-cols-2"><input name="issued_on" type="date" class="rounded border-slate-300 px-4 py-3"><input name="expires_on" type="date" class="rounded border-slate-300 px-4 py-3"></div>
                                <input name="file" type="file" class="rounded border border-slate-300 px-4 py-3">
                                <button class="rounded bg-[#2a7190] px-4 py-2 font-bold text-white">Add certificate</button>
                            </form>

                            <form method="POST" action="{{ route('candidate.profile.projects.store') }}" class="grid gap-3 rounded border border-slate-200 p-4">
                                @csrf
                                <p class="font-bold">Project</p>
                                <input name="title" required class="rounded border-slate-300 px-4 py-3" placeholder="Project title">
                                <input name="role" class="rounded border-slate-300 px-4 py-3" placeholder="Your role">
                                <input name="url" type="url" class="rounded border-slate-300 px-4 py-3" placeholder="Project URL">
                                <input name="skills" class="rounded border-slate-300 px-4 py-3" placeholder="Skills, comma separated">
                                <div class="grid gap-3 sm:grid-cols-2"><input name="started_on" type="date" class="rounded border-slate-300 px-4 py-3"><input name="ended_on" type="date" class="rounded border-slate-300 px-4 py-3"></div>
                                <textarea name="description" rows="3" class="rounded border-slate-300 px-4 py-3" placeholder="What you built or contributed"></textarea>
                                <button class="rounded bg-[#2a7190] px-4 py-2 font-bold text-white">Add project</button>
                            </form>

                            <form method="POST" action="{{ route('candidate.profile.portfolio.store') }}" enctype="multipart/form-data" class="grid gap-3 rounded border border-slate-200 p-4 lg:col-span-2">
                                @csrf
                                <p class="font-bold">Portfolio item</p>
                                <div class="grid gap-3 md:grid-cols-2">
                                    <input name="title" required class="rounded border-slate-300 px-4 py-3" placeholder="Item title">
                                    <select name="type" required class="rounded border-slate-300 px-4 py-3">
                                        <option value="link">Link</option>
                                        <option value="file">File</option>
                                        <option value="image">Image</option>
                                        <option value="video">Video</option>
                                    </select>
                                    <input name="url" type="url" class="rounded border-slate-300 px-4 py-3" placeholder="URL">
                                    <input name="file" type="file" class="rounded border border-slate-300 px-4 py-3">
                                </div>
                                <textarea name="description" rows="3" class="rounded border-slate-300 px-4 py-3" placeholder="Description"></textarea>
                                <button class="w-fit rounded bg-[#2a7190] px-4 py-2 font-bold text-white">Add portfolio item</button>
                            </form>
                        </div>
                    </div>

                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold">Job alerts</h2>
                        <form method="POST" action="{{ route('candidate.alerts.store') }}" class="mt-4 grid gap-3 md:grid-cols-5">
                            @csrf
                            <input name="name" required class="rounded border-slate-300 px-4 py-3" placeholder="Alert name">
                            <input name="keyword" class="rounded border-slate-300 px-4 py-3" placeholder="Keyword">
                            <select name="country" class="rounded border-slate-300 px-4 py-3">
                                <option value="">Any country</option>
                                @foreach ($portal['countries'] as $country)
                                    <option value="{{ $country }}">{{ $country }}</option>
                                @endforeach
                            </select>
                            <select name="frequency" class="rounded border-slate-300 px-4 py-3">
                                <option value="daily">Daily</option>
                                <option value="weekly" selected>Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                            <button class="rounded bg-[#2a7190] px-4 py-2 font-bold text-white">Create alert</button>
                        </form>
                        <div class="mt-5 grid gap-3">
                            @forelse (auth()->user()->jobAlerts()->latest()->get() as $alert)
                                <div class="flex flex-col justify-between gap-3 rounded border border-slate-200 p-4 sm:flex-row sm:items-center">
                                    <div>
                                        <p class="font-bold">{{ $alert->name }}</p>
                                        <p class="mt-1 text-sm text-slate-600">{{ $alert->keyword ?: 'Any keyword' }} · {{ $alert->country ?: 'Any country' }} · {{ str($alert->frequency)->headline() }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('candidate.alerts.destroy', $alert) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded border border-red-200 bg-red-50 px-4 py-2 text-sm font-bold text-red-700">Remove</button>
                                    </form>
                                </div>
                            @empty
                                <p class="rounded border border-dashed border-slate-300 p-4 text-sm text-slate-600">No job alerts yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
