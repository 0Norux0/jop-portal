<?php

declare(strict_types=1);

namespace App\Filament\Resources\TrustReportResource\Pages;

use App\Filament\Resources\TrustReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTrustReport extends CreateRecord
{
    protected static string $resource = TrustReportResource::class;
}
