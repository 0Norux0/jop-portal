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
        'user_id',
        'full_name',
        'headline',
        'email',
        'phone',
        'country',
        'city',
        'current_job_title',
        'preferred_job_category',
        'linkedin_url',
        'portfolio_url',
        'cv_path',
        'verification_status',
        'availability_status',
        'trust_score',
        'skills',
        'bio',
    ];

    protected $casts = [
        'skills' => 'array',
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
}
