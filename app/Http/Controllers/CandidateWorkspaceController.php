<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Identity\Models\User;
use App\Domain\Portal\Models\Candidate;
use App\Domain\Portal\Models\ConversationMessage;
use App\Domain\Portal\Models\Job;
use App\Domain\Portal\Models\JobApplication;
use App\Domain\Portal\Models\SavedJob;
use App\Support\PortalJobPresenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CandidateWorkspaceController
{
    public function dashboard(Request $request): View
    {
        $user = $this->user($request);

        return view('dashboard', [
            'portal' => ['jobs' => PortalJobPresenter::publishedJobs()],
            'savedJobs' => $this->savedJobCollection($user)->take(2),
            'applications' => $this->applicationCollection($user)->take(2),
            'messages' => $this->messageCollection($user)->take(3),
        ]);
    }

    public function saveJob(Request $request, Job $job): RedirectResponse
    {
        abort_unless($job->status === 'published', 404);

        SavedJob::query()->firstOrCreate(
            ['user_id' => $this->user($request)->id, 'job_id' => $job->id],
            ['saved_at' => now()],
        );

        return back()->with('status', 'Job saved.');
    }

    public function unsaveJob(Request $request, SavedJob $savedJob): RedirectResponse
    {
        abort_unless($savedJob->user_id === $this->user($request)->id, 403);

        $savedJob->delete();

        return back()->with('status', 'Saved job removed.');
    }

    public function apply(Request $request, Job $job): RedirectResponse
    {
        abort_unless($job->status === 'published', 404);

        $user = $this->user($request);
        $candidate = $this->candidate($user);
        $validated = $request->validate([
            'cover_letter' => ['nullable', 'string', 'max:3000'],
        ]);

        JobApplication::query()->firstOrCreate(
            ['job_id' => $job->id, 'user_id' => $user->id],
            [
                'candidate_id' => $candidate?->id,
                'candidate_name' => $user->name,
                'candidate_email' => $user->email,
                'method' => 'portal_profile',
                'status' => 'submitted',
                'linkedin_url' => $user->linkedin_url,
                'cover_letter' => $validated['cover_letter'] ?? null,
            ],
        );

        return redirect()->route('candidate.applications')->with('status', 'Application submitted.');
    }

    public function savedJobs(Request $request): View
    {
        return view('candidate.saved-jobs', [
            'savedJobs' => $this->savedJobCollection($this->user($request)),
        ]);
    }

    public function applications(Request $request): View
    {
        return view('candidate.applications', [
            'applications' => $this->applicationCollection($this->user($request)),
        ]);
    }

    public function messages(Request $request): View
    {
        $user = $this->user($request);
        $messages = $this->messageCollection($user);

        ConversationMessage::query()
            ->where('candidate_user_id', $user->id)
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('candidate.messages', ['messages' => $messages]);
    }

    public function sendMessage(Request $request): RedirectResponse
    {
        $user = $this->user($request);
        $validated = $request->validate([
            'employer_id' => ['required', 'exists:employers,id'],
            'job_application_id' => ['nullable', 'exists:job_applications,id'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $applicationId = $validated['job_application_id'] ?? null;

        if ($applicationId !== null) {
            abort_unless(JobApplication::query()->whereKey($applicationId)->where('user_id', $user->id)->exists(), 403);
        }

        ConversationMessage::query()->create([
            'employer_id' => $validated['employer_id'],
            'candidate_user_id' => $user->id,
            'job_application_id' => $applicationId,
            'sender_id' => $user->id,
            'body' => $validated['body'],
        ]);

        return back()->with('status', 'Message sent.');
    }

    private function user(Request $request): User
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        return $user;
    }

    private function candidate(User $user): ?Candidate
    {
        return Candidate::query()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'country' => $user->country,
                'city' => $user->city,
                'current_job_title' => $user->current_job_title,
                'preferred_job_category' => $user->preferred_job_category,
                'linkedin_url' => $user->linkedin_url,
                'portfolio_url' => $user->portfolio_url,
                'verification_status' => 'unverified',
                'availability_status' => 'open_to_work',
                'trust_score' => 20,
                'skills' => [],
            ],
        );
    }

    private function savedJobCollection(User $user)
    {
        return SavedJob::query()
            ->with(['job.employer', 'job.category'])
            ->where('user_id', $user->id)
            ->latest('saved_at')
            ->get();
    }

    private function applicationCollection(User $user)
    {
        return JobApplication::query()
            ->with(['job.employer'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();
    }

    private function messageCollection(User $user)
    {
        return ConversationMessage::query()
            ->with(['employer', 'sender', 'application.job'])
            ->where('candidate_user_id', $user->id)
            ->latest()
            ->get();
    }
}
