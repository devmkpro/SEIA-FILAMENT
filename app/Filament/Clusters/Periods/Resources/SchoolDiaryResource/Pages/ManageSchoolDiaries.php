<?php

namespace App\Filament\Clusters\Periods\Resources\SchoolDiaryResource\Pages;

use App\Filament\Clusters\Periods\Resources\SchoolDiaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSchoolDiaries extends ManageRecords
{
    protected static string $resource = SchoolDiaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
