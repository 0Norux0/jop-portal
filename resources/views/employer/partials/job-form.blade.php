<div>
    <label class="text-sm font-bold">Job title</label>
    <input name="title" value="{{ old('title', $job?->title) }}" required class="mt-2 w-full rounded border-slate-300 px-4 py-3">
</div>
<div>
    <label class="text-sm font-bold">Category</label>
    <select name="job_category_id" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
        <option value="">Select category</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected((string) old('job_category_id', $job?->job_category_id) === (string) $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
</div>
<div>
    <label class="text-sm font-bold">Country</label>
    <input name="country" value="{{ old('country', $job?->country) }}" list="job-countries" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
    <datalist id="job-countries">
        @foreach ($portal['countries'] as $country)
            <option value="{{ $country }}"></option>
        @endforeach
    </datalist>
</div>
<div>
    <label class="text-sm font-bold">City</label>
    <input name="city" value="{{ old('city', $job?->city) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
</div>
<div>
    <label class="text-sm font-bold">Work mode</label>
    <select name="work_mode" required class="mt-2 w-full rounded border-slate-300 px-4 py-3">
        @foreach (['on_site' => 'On-site', 'remote' => 'Remote', 'hybrid' => 'Hybrid'] as $value => $label)
            <option value="{{ $value }}" @selected(old('work_mode', $job?->work_mode ?? 'on_site') === $value)>{{ $label }}</option>
        @endforeach
    </select>
</div>
<div>
    <label class="text-sm font-bold">Employment type</label>
    <select name="employment_type" required class="mt-2 w-full rounded border-slate-300 px-4 py-3">
        @foreach (['full_time' => 'Full-time', 'part_time' => 'Part-time', 'contract' => 'Contract', 'freelance' => 'Freelance', 'internship' => 'Internship'] as $value => $label)
            <option value="{{ $value }}" @selected(old('employment_type', $job?->employment_type ?? 'full_time') === $value)>{{ $label }}</option>
        @endforeach
    </select>
</div>
<div>
    <label class="text-sm font-bold">Currency</label>
    <input name="currency" value="{{ old('currency', $job?->currency) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="KWD, USD, GBP">
</div>
<div class="grid grid-cols-2 gap-3">
    <div>
        <label class="text-sm font-bold">Salary min</label>
        <input name="salary_min" type="number" min="0" value="{{ old('salary_min', $job?->salary_min) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
    </div>
    <div>
        <label class="text-sm font-bold">Salary max</label>
        <input name="salary_max" type="number" min="0" value="{{ old('salary_max', $job?->salary_max) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
    </div>
</div>
<div>
    <label class="text-sm font-bold">Vacancies</label>
    <input name="vacancies" type="number" min="1" value="{{ old('vacancies', $job?->vacancies ?? 1) }}" required class="mt-2 w-full rounded border-slate-300 px-4 py-3">
</div>
<div>
    <label class="text-sm font-bold">Application deadline</label>
    <input name="application_deadline" type="date" value="{{ old('application_deadline', $job?->application_deadline?->format('Y-m-d')) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
</div>
<div class="sm:col-span-2">
    <label class="text-sm font-bold">Description</label>
    <textarea name="description" rows="5" class="mt-2 w-full rounded border-slate-300 px-4 py-3">{{ old('description', $job?->description) }}</textarea>
</div>
@foreach ([
    'responsibilities' => 'Responsibilities',
    'skills' => 'Required skills',
    'requirements' => 'Requirements',
    'benefits' => 'Benefits',
    'applicant_questions' => 'Applicant questions',
] as $field => $label)
    <div class="sm:col-span-2">
        <label class="text-sm font-bold">{{ $label }}</label>
        <textarea name="{{ $field }}" rows="3" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="One item per line">{{ old($field, implode("\n", $job?->{$field} ?? [])) }}</textarea>
    </div>
@endforeach
<div class="sm:col-span-2 flex flex-wrap gap-3 text-sm font-semibold">
    <label class="rounded border border-slate-200 px-4 py-3"><input type="checkbox" name="visa_sponsorship" value="1" @checked(old('visa_sponsorship', $job?->visa_sponsorship))> Visa sponsorship</label>
    <label class="rounded border border-slate-200 px-4 py-3"><input type="checkbox" name="relocation_support" value="1" @checked(old('relocation_support', $job?->relocation_support))> Relocation support</label>
    <label class="rounded border border-slate-200 px-4 py-3"><input type="checkbox" name="is_urgent" value="1" @checked(old('is_urgent', $job?->is_urgent))> Urgent</label>
</div>
