<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Regioes extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-americas';

    public static function getNavigationLabel(): string
    {
        return __('Regiões');
    }
}
