<?php

declare(strict_types=1);

namespace App\Domain\Portal\Models;

use App\Domain\Shared\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;
    use HasPublicId;

    protected $table = 'job_posts';

    protected $fillable = [
        'public_id',
        'employer_id',
        'job_category_id',
        'title',
        'slug',
        'country',
        'city',
        'work_mode',
        'employment_type',
        'currency',
        'salary_min',
        'salary_max',
        'vacancies',
        'application_deadline',
        'status',
        'is_featured',
        'is_urgent',
        'promotion_status',
        'published_at',
        'visa_sponsorship',
        'relocation_support',
        'description',
        'responsibilities',
        'skills',
        'requirements',
        'benefits',
        'applicant_questions',
    ];

    protected $casts = [
        'application_deadline' => 'date',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_urgent' => 'boolean',
        'visa_sponsorship' => 'boolean',
        'relocation_support' => 'boolean',
        'salary_min' => 'integer',
        'salary_max' => 'integer',
        'vacancies' => 'integer',
        'responsibilities' => 'array',
        'skills' => 'array',
        'requirements' => 'array',
        'benefits' => 'array',
        'applicant_questions' => 'array',
    ];

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function savedJobs(): HasMany
    {
        return $this->hasMany(SavedJob::class);
    }
}
