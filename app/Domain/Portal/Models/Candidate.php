<?php

declare(strict_types=1);

namespace App\Domain\Portal\Models;

use App\Domain\Identity\Models\User;
use App\Domain\Shared\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    use HasFactory;
    use HasPublicId;

    protected $fillable = [
        'public_id',
        'slug',
        'user_id',
        'full_name',
        'headline',
        'email',
        'phone',
        'country',
        'city',
        'current_job_title',
        'preferred_job_category',
        'preferred_locations',
        'employment_type_preference',
        'work_mode_preference',
        'work_authorization',
        'visa_requirements',
        'relocation_preference',
        'linkedin_url',
        'portfolio_url',
        'cv_path',
        'video_path',
        'verification_status',
        'availability_status',
        'expected_salary',
        'notice_period',
        'is_public',
        'trust_score',
        'skills',
        'languages',
        'external_links',
        'bio',
    ];

    protected $casts = [
        'skills' => 'array',
        'languages' => 'array',
        'preferred_locations' => 'array',
        'external_links' => 'array',
        'is_public' => 'boolean',
        'trust_score' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function portfolioItems(): HasMany
    {
        return $this->hasMany(CandidatePortfolioItem::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(CandidateProject::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(CandidateCertificate::class);
    }

    public function educations(): HasMany
    {
        return $this->hasMany(CandidateEducation::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(CandidateExperience::class);
    }
}
