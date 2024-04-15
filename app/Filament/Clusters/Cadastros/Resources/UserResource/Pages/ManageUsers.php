<?php

namespace App\Filament\Clusters\Cadastros\Resources\UserResource\Pages;

use App\Filament\Clusters\Cadastros\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
