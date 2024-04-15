<?php

namespace App\Filament\Clusters\Regioes\Resources\CityResource\Pages;

use App\Filament\Clusters\Regioes\Resources\CityResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCities extends ManageRecords
{
    protected static string $resource = CityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
