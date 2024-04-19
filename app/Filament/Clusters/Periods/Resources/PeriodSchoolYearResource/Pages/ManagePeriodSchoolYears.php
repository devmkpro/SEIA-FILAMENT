<?php

namespace App\Filament\Clusters\Periods\Resources\PeriodSchoolYearResource\Pages;

use App\Filament\Clusters\Periods\Resources\PeriodSchoolYearResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePeriodSchoolYears extends ManageRecords
{
    protected static string $resource = PeriodSchoolYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
