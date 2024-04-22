<?php

namespace App\Filament\Clusters\Periods\Resources\PeriodBimonthlyResource\Pages;

use App\Filament\Clusters\Periods\Resources\PeriodBimonthlyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeriodBimonthly extends EditRecord
{
    protected static string $resource = PeriodBimonthlyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
