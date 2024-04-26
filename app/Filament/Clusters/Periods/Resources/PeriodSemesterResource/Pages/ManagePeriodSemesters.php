<?php

namespace App\Filament\Clusters\Periods\Resources\PeriodSemesterResource\Pages;

use App\Filament\Clusters\Periods\Resources\PeriodSemesterResource;
use App\Filament\Resources\SchoolResource;
use App\Models\PeriodSchoolYear;
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
                ->action(function ($data) {
                    DB::transaction(function () use ($data) {
                        $periodSchoolYear = PeriodSchoolYear::where('id', $data['period_school_years_id'])
                            ->where('school_id', SchoolResource::getSchoolId())
                            ->where('type', 'Semestral')
                            ->first();

                        if (!$periodSchoolYear) {
                            Notification::make()
                                ->danger()
                                ->title(__('The selected school year is not a semester type'))
                                ->send();
                            return;
                        } else if ($periodSchoolYear->active == 'Inativa') {
                            Notification::make()
                                ->danger()
                                ->title(__('The selected school year is inactive'))
                                ->send();
                            return;
                        } else {
                            $semester = self::createPeriodSemester($data);
                            $school_diary = self::createSchoolDiary($data);
                            self::createSemesterSchoolDiary($school_diary->id, $semester->id);
                            self::sendSuccessNotification();
                        }
                    });
                })
        ];

       
    }
    private static function createPeriodSemester($data)
    {
        return \App\Models\PeriodSemester::create([
            'period_school_years_id' => $data['period_school_years_id'],
            'semester' => $data['semester'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'active' => $data['active'],
        ]);
    }

    private static function createSchoolDiary($data)
    {
        return \App\Models\SchoolDiary::create([
            'active' => $data['active'],
            'school_id' =>  SchoolResource::getSchoolId()
        ]);
    }

    private static function createSemesterSchoolDiary($school_diary_id, $semester_id)
    {
        \App\Models\SemesterSchoolDiary::create([
            'school_diary_id' => $school_diary_id,
            'period_semesters_id' => $semester_id,
        ]);
    }

    private static function sendSuccessNotification()
    {
        Notification::make()
            ->success()
            ->title(__('Semester created successfully'))
            ->send();
    }
}
