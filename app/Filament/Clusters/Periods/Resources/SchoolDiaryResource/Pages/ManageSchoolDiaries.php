<?php

namespace App\Filament\Clusters\Periods\Resources\SchoolDiaryResource\Pages;

use App\Filament\Clusters\Periods\Resources\SchoolDiaryResource;
use App\Models\PeriodSchoolYear;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ManageSchoolDiaries extends ManageRecords
{
    protected static string $resource = SchoolDiaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
        //     Actions\CreateAction::make()
        //         ->action(
        //             function ($data) {
        //                 $period = PeriodSchoolYear::where('id', $data['period_school_years_id'])->first();

        //                 DB::transaction(
        //                     function () use ($data, $period) {
        //                         $school_diary = \App\Models\SchoolDiary::create([
        //                             'active' => $data['active'],
        //                             'school_id' => $data['school_id']
        //                         ]);

        //                         if ($period->type == 'Bimestral') {
        //                             \App\Models\BimesterSchoolDiary::create([
        //                                 'school_diary_id' => $school_diary->id,
        //                                 'period_bimonthlies_id' => $data['bimester_id']
        //                             ]);
        //                         }
        //                     }
        //                 );

        //                 Notification::make()
        //                     ->success()
        //                     ->title(__('School Diary created successfully'))
        //                     ->send();
        //             }
        //         )
        ];
    }
}
