<?php

declare(strict_types=1);

namespace App\Domain\Portal\Models;

use App\Domain\Shared\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployerServiceRequest extends Model
{
    use HasPublicId;

    protected $fillable = [
        'public_id',
        'employer_id',
        'job_id',
        'candidate_id',
        'type',
        'title',
        'status',
        'budget',
        'payload',
        'notes',
        'reviewed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'budget' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
