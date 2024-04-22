<?php

namespace App\Filament\Clusters\Periods\Resources\PeriodBimonthlyResource\Pages;

use App\Filament\Clusters\Periods\Resources\PeriodBimonthlyResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePeriodBimonthlies extends ManageRecords
{
    protected static string $resource = PeriodBimonthlyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
