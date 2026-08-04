<?php

declare(strict_types=1);

namespace App\Domain\Portal\Models;

use App\Domain\Shared\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employer extends Model
{
    use HasFactory;
    use HasPublicId;

    protected $fillable = [
        'public_id',
        'name',
        'slug',
        'industry',
        'country',
        'city',
        'website_url',
        'contact_name',
        'contact_email',
        'contact_phone',
        'verification_status',
        'status',
        'description',
        'notes',
    ];

    protected $casts = [
        'notes' => 'array',
    ];

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }
}
