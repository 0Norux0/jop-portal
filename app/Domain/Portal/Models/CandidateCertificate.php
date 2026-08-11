<?php

declare(strict_types=1);

namespace App\Domain\Portal\Models;

use App\Domain\Shared\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateCertificate extends Model
{
    use HasPublicId;

    protected $fillable = ['public_id', 'candidate_id', 'name', 'issuer', 'credential_number', 'credential_url', 'file_path', 'issued_on', 'expires_on', 'verification_status'];

    protected $casts = [
        'issued_on' => 'date',
        'expires_on' => 'date',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
