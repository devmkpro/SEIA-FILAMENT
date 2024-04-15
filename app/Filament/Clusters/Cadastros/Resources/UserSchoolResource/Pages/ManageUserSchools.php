<?php

namespace App\Filament\Clusters\Cadastros\Resources\UserSchoolResource\Pages;

use App\Filament\Clusters\Cadastros\Resources\UserSchoolResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageUserSchools extends ManageRecords
{
    protected static string $resource = UserSchoolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
