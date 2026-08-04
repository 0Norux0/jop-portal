<?php

declare(strict_types=1);

namespace App\Domain\Shared\Concerns;

use Illuminate\Support\Str;

/**
 * Gives a model a non-sequential public identifier (ULID) used in URLs,
 * keeping the internal numeric primary key out of public routes.
 */
trait HasPublicId
{
    public static function bootHasPublicId(): void
    {
        static::creating(function ($model): void {
            if (empty($model->public_id)) {
                $model->public_id = (string) Str::ulid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }
}
