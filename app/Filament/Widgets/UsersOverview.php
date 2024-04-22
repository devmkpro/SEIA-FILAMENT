<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsersOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '5s';

    protected function getStats(): array
    {
        return [
            Stat::make(__('Total Users'), User::query()->count())
                ->description(
                    __('The total number of users in the system.')
                )
                ->descriptionIcon('heroicon-o-users'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->isAdmin();
    }
}
