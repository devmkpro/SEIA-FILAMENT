<?php

namespace App\Filament\Resources\SchoolResource\Pages;

use App\Filament\Resources\SchoolResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateSchool extends CreateRecord
{
    protected static string $resource = SchoolResource::class;
}
