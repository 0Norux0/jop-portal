<?php

declare(strict_types=1);

namespace App\Domain\Portal\Models;

use App\Domain\Shared\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployerCreditTransaction extends Model
{
    use HasPublicId;

    protected $fillable = [
        'public_id',
        'employer_id',
        'candidate_id',
        'job_id',
        'credit_type',
        'amount',
        'description',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'amount' => 'integer',
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
