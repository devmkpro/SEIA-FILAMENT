<?php

namespace App\Filament\Clusters\Periods\Resources\PeriodSemesterResource\Pages;

use App\Filament\Clusters\Periods\Resources\PeriodSemesterResource;
use App\Filament\Resources\SchoolResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\DB;

class ManagePeriodSemesters extends ManageRecords
{
    protected static string $resource = PeriodSemesterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label(__('Create Semester'))
            ->action(
                function ($data) {
                    DB::transaction(
                        function () use ($data) {
                            $semester = \App\Models\PeriodSemester::create([
                                'period_school_years_id' => $data['period_school_years_id'],
                                'semester' => $data['semester'],
                                'start_date' => $data['start_date'],
                                'end_date' => $data['end_date'],
                                'active' => $data['active'],
                            ]);

                            $school_diary = \App\Models\SchoolDiary::create([
                                'active' => $data['active'],
                                'school_id' =>  SchoolResource::getSchoolId()
                            ]);

                            \App\Models\SemesterSchoolDiary::create([
                                'school_diary_id' => $school_diary->id,
                                'period_semesters_id' => $semester->id,
                            ]);
                        }
                    );

                    Notification::make()
                        ->success()
                        ->title(__('Semester created successfully'))
                        ->send();

                }
            )
        ];
    }
}
