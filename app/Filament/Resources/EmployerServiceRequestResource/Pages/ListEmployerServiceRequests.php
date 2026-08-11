<?php

declare(strict_types=1);

namespace App\Filament\Resources\EmployerServiceRequestResource\Pages;

use App\Filament\Resources\EmployerServiceRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployerServiceRequests extends ListRecords
{
    protected static string $resource = EmployerServiceRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
