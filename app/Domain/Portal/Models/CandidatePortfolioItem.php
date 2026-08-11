<?php

declare(strict_types=1);

namespace App\Domain\Portal\Models;

use App\Domain\Shared\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidatePortfolioItem extends Model
{
    use HasPublicId;

    protected $fillable = ['public_id', 'candidate_id', 'title', 'type', 'url', 'file_path', 'description', 'sort_order'];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
