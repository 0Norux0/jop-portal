<?php

declare(strict_types=1);

namespace App\Domain\Portal\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'title',
        'eyebrow',
        'description',
        'is_enabled',
        'sort_order',
        'content',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'sort_order' => 'integer',
        'content' => 'array',
    ];
}
