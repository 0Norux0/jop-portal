<?php

declare(strict_types=1);

namespace App\Domain\Portal\Models;

use App\Domain\Shared\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateProject extends Model
{
    use HasPublicId;

    protected $fillable = ['public_id', 'candidate_id', 'title', 'role', 'url', 'description', 'skills', 'started_on', 'ended_on'];

    protected $casts = [
        'skills' => 'array',
        'started_on' => 'date',
        'ended_on' => 'date',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
