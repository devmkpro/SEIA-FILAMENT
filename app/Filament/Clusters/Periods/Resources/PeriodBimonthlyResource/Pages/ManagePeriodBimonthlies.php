<?php

namespace App\Filament\Clusters\Periods\Resources\PeriodBimonthlyResource\Pages;

use App\Filament\Clusters\Periods\Resources\PeriodBimonthlyResource;
use App\Filament\Resources\SchoolResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\DB;

class ManagePeriodBimonthlies extends ManageRecords
{
    protected static string $resource = PeriodBimonthlyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->action(
                function ($data) {
                    DB::transaction(
                        function () use ($data) {
                            $bimester = \App\Models\PeriodBimonthly::create([
                                'period_school_years_id' => $data['period_school_years_id'],
                                'bimester' => $data['bimester'],
                                'start_date' => $data['start_date'],
                                'end_date' => $data['end_date'],
                                'active' => $data['active'],
                            ]);

                            $school_diary = \App\Models\SchoolDiary::create([
                                'active' => $data['active'],
                                'school_id' =>  SchoolResource::getSchoolId()
                            ]);

                            \App\Models\BimesterSchoolDiary::create([
                                'school_diary_id' => $school_diary->id,
                                'period_bimonthlies_id' => $bimester->id,
                            ]);
                        }
                    );

                    Notification::make()
                        ->success()
                        ->title(__('Bi-monthly created successfully'))
                        ->send();
                }
            )
        ];
    }
}
