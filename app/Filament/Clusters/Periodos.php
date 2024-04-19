<?php

namespace App\Filament\Clusters;

use App\Http\Middleware\CheckSchoolCookieForPages;
use App\Models\School;
use Filament\Clusters\Cluster;
use Illuminate\Support\Facades\Redirect;

class Periodos extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Secretaria';

    public static function canAccess(): bool
    {
        $middleware = new CheckSchoolCookieForPages();
        $isValid = $middleware->handle(request(), function ($request) {
            return false;
        });

        return $isValid;
    }

    public static function redirectIfNotCanAccess(): void
    {
        if (!request()->cookie('SHID')) {
            Redirect::route('select-school');
        }

        $school = School::where('code', request()->cookie('SHID'))->first();

        if (!$school) {
            Redirect::route('select-school');
        }
    }

    public static function getNavigationLabel(): string
    {
        return __('Períodos');
    }
}
