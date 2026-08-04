<?php

declare(strict_types=1);

namespace App\Filament\Resources\EmployerResource\Pages;

use App\Filament\Resources\EmployerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployer extends CreateRecord
{
    protected static string $resource = EmployerResource::class;
}
