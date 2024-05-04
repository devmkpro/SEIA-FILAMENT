<?php

namespace App\Filament\Clusters\Periods\Resources\PeriodBimonthlyResource\Pages;

use App\Filament\Clusters\Periods\Resources\PeriodBimonthlyResource;
use App\Filament\Resources\SchoolResource;
use App\Models\PeriodSchoolYear;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ManagePeriodBimonthlies extends ManageRecords
{
    protected static string $resource = PeriodBimonthlyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('Create Bi-monthly'))
                ->action(
                    function ($data) {
                        DB::transaction(
                            function () use ($data) {

                                $periodSchoolYear = PeriodSchoolYear::where('id', $data['period_school_years_id'])
                                    ->where('school_id', SchoolResource::getSchoolId())
                                    ->where('type', 'Bimestral')
                                    ->first();

                                if (!$periodSchoolYear) {
                                    Notification::make()
                                        ->danger()
                                        ->title(__('The selected school year is not a bi-monthly type'))
                                        ->send();
                                    return;
                                } else if ($periodSchoolYear->active == 'Inativa') {
                                    Notification::make()
                                        ->danger()
                                        ->title(__('The selected school year is inactive'))
                                        ->send();
                                    return;
                                } else {
                                    $bimester = self::createPeriodBimonthly($data);
                                    $school_diary = self::createSchoolDiary($data);
                                    self::createBimesterSchoolDiary($school_diary->id, $bimester->id);
                                    self::sendSuccessNotification();
                                }
                               
                            }
                        );
                    }
                )->after(function () {
                    return Redirect::to('/admin/periods/period-bimonthlies');
                }),
        ];
    }

    private static function createPeriodBimonthly($data)
    {
        return \App\Models\PeriodBimonthly::create([
            'period_school_years_id' => $data['period_school_years_id'],
            'bimester' => $data['bimester'],
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

    private static function createBimesterSchoolDiary($school_diary_id, $bimester_id)
    {
        \App\Models\BimesterSchoolDiary::create([
            'school_diary_id' => $school_diary_id,
            'period_bimonthlies_id' => $bimester_id,
        ]);
    }

    private static function sendSuccessNotification()
    {
        Notification::make()
            ->success()
            ->title(__('Bi-monthly created successfully'))
            ->send();
    }
}
