<?php

declare(strict_types=1);

namespace App\Domain\Portal\Models;

use App\Domain\Shared\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateExperience extends Model
{
    use HasPublicId;

    protected $fillable = ['public_id', 'candidate_id', 'company', 'title', 'location', 'started_on', 'ended_on', 'is_current', 'description'];

    protected $casts = [
        'started_on' => 'date',
        'ended_on' => 'date',
        'is_current' => 'boolean',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
