<?php

namespace App\Filament\Clusters\Periods\Resources\PeriodBimonthlyResource\Pages;

use App\Filament\Clusters\Periods\Resources\PeriodBimonthlyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePeriodBimonthly extends CreateRecord
{
    protected static string $resource = PeriodBimonthlyResource::class;
}
