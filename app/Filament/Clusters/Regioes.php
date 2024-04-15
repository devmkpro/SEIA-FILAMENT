<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use Filament\Pages\SubNavigationPosition;

class Regioes extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-americas';

    protected static ?string $navigationGroup  = 'Administrativo';

    public static function getNavigationLabel(): string
    {
        return __('Regiões');
    }
}
