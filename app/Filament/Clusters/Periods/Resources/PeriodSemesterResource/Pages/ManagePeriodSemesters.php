<?php

namespace App\Filament\Clusters\Periods\Resources\PeriodSemesterResource\Pages;

use App\Filament\Clusters\Periods\Resources\PeriodSemesterResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePeriodSemesters extends ManageRecords
{
    protected static string $resource = PeriodSemesterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
