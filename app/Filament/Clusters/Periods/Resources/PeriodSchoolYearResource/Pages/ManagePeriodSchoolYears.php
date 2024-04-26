<?php

namespace App\Filament\Clusters\Periods\Resources\PeriodSchoolYearResource\Pages;

use App\Filament\Clusters\Periods\Resources\PeriodSchoolYearResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Redirect;

class ManagePeriodSchoolYears extends ManageRecords
{
    protected static string $resource = PeriodSchoolYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label(__('Create School Period'))
            ->after(function () {
                return Redirect::to('/admin/periods/period-school-years');
            }),
        ];
    }
}
