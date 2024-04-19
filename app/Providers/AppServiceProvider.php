<?php

namespace App\Providers;

use App\Models\School;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('Administrativo')
                    ->collapsed(),

                NavigationGroup::make()
                    ->label('Secretaria')
                    ->collapsed(),
            ]);

            view()->composer('*', function ($view) {
                $school_cookie = request()->cookie('SHID');
                $school_home = null;

                if ($school_cookie) {
                    $school_home = School::where('code', $school_cookie)->first();
                }

                $view->with('school_home', $school_home);
            });
        });
    }
}
