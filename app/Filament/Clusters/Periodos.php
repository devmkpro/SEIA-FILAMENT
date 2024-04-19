<?php

namespace App\Filament\Clusters;

use App\Http\Middleware\CheckSchoolCookieForPages;
use App\Http\Middleware\RequireSchoolCookie;
use App\Models\School;
use Filament\Clusters\Cluster;
use Illuminate\Support\Facades\Redirect;

class Periodos extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Secretaria';

    public static function canAccess(): bool
    {
        $isValid = (new RequireSchoolCookie())->handle(request(), function ($request) {
            return false;
        });

        if (!$isValid) {
            return false;
        }

        $isValid = (new CheckSchoolCookieForPages())->handle(request(), function ($request) {
            return false;
        });

        if (!$isValid) {
            return false;
        }

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
