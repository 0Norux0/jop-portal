<?php

declare(strict_types=1);

namespace App\Filament\Resources\TrustReportResource\Pages;

use App\Filament\Resources\TrustReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTrustReports extends ListRecords
{
    protected static string $resource = TrustReportResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
