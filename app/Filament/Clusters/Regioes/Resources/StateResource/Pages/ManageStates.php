<?php

namespace App\Filament\Clusters\Regioes\Resources\StateResource\Pages;

use App\Filament\Clusters\Regioes\Resources\StateResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageStates extends ManageRecords
{
    protected static string $resource = StateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
