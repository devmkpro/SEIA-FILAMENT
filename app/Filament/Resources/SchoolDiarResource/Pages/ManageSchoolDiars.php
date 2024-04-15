<?php

namespace App\Filament\Resources\SchoolDiarResource\Pages;

use App\Filament\Resources\SchoolDiarResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSchoolDiars extends ManageRecords
{
    protected static string $resource = SchoolDiarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
