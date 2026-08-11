<?php

declare(strict_types=1);

namespace App\Filament\Resources\EmployerServiceRequestResource\Pages;

use App\Filament\Resources\EmployerServiceRequestResource;
use Filament\Resources\Pages\EditRecord;

class EditEmployerServiceRequest extends EditRecord
{
    protected static string $resource = EmployerServiceRequestResource::class;
}
