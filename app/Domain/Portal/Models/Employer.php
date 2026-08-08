<?php

declare(strict_types=1);

namespace App\Domain\Portal\Models;

use App\Domain\Identity\Models\User;
use App\Domain\Shared\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employer extends Model
{
    use HasFactory;
    use HasPublicId;

    protected $fillable = [
        'public_id',
        'user_id',
        'name',
        'slug',
        'industry',
        'company_size',
        'country',
        'city',
        'website_url',
        'logo_path',
        'cover_path',
        'contact_name',
        'contact_email',
        'contact_phone',
        'billing_email',
        'billing_plan',
        'premium_status',
        'advertising_enabled',
        'learning_enabled',
        'verification_status',
        'status',
        'is_published',
        'description',
        'notes',
        'social_links',
    ];

    protected $casts = [
        'notes' => 'array',
        'social_links' => 'array',
        'is_published' => 'boolean',
        'advertising_enabled' => 'boolean',
        'learning_enabled' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }
}
