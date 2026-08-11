<?php

declare(strict_types=1);

namespace App\Domain\Portal\Models;

use App\Domain\Shared\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateEducation extends Model
{
    use HasPublicId;

    protected $fillable = ['public_id', 'candidate_id', 'school', 'degree', 'field', 'started_on', 'ended_on', 'description'];

    protected $casts = [
        'started_on' => 'date',
        'ended_on' => 'date',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
