<?php

namespace App\Filament\Clusters\Periods\Resources;

use App\Filament\Clusters\Periods;
use App\Filament\Clusters\Periods\Resources\SchoolDiaryResource\Pages;
use App\Filament\Clusters\Periods\Resources\utils\SchoolPermissionAccess;
use App\Filament\Resources\SchoolResource;
use App\Models\PeriodSchoolYear;
use App\Models\SchoolDiary;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Support\Facades\Redirect;

class SchoolDiaryResource extends Resource
{
    protected static ?string $model = SchoolDiary::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $cluster = Periods::class;

    public static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getModelLabel(): string
    {
        return __('School Diary');
    }

    public static function getSchoolId(): int
    {
        return SchoolResource::getSchoolId();
    }

    public static function getSchoolYearId(): int
    {
        return SchoolResource::getSchoolYearId();
    }

    public static function canAccess(): bool
    {

        $isValid = (new SchoolPermissionAccess())->canAccess();
        if (!$isValid) {
            return false;
        }

        $periodSchoolYear = PeriodSchoolYear::where('school_year_id', self::getSchoolYearId())->where(
            'school_id',
            self::getSchoolId()
        )
            ->first();

        if (!$periodSchoolYear || ($periodSchoolYear->type == 'Bimestral' && !$periodSchoolYear->bimesters()->exists()) || ($periodSchoolYear->type == 'Semestral' && !$periodSchoolYear->semesters()->exists())) {
            return false;
        }

        return true;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SchoolResource::makeActiveTableColumn(),
                Tables\Columns\TextColumn::make('school.name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('bimester')
                    ->formatStateUsing(
                        fn ($record) => $record->bimester->pluck('bimester')->implode(', ')
                    )
                    ->label('Período')
                    ->visible(
                        fn () => PeriodSchoolYear::where('school_id', self::getSchoolId())->where('school_year_id', self::getSchoolYearId())->first()->type == 'Bimestral'
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('semester')
                    ->formatStateUsing(
                        fn ($record) => $record->semester->pluck('semester')->implode(', ')
                    )
                    ->label('Período')
                    ->visible(
                        fn () => PeriodSchoolYear::where('school_id', self::getSchoolId())->where('school_year_id', self::getSchoolYearId())->first()->type == 'Semestral'
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                self::makeActionInactive(),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                
            ]);
    }

    private static function makeActionInactive(): Action
    {
        return Action::make('toggle-active')
            ->label(
                fn ($record) => $record->active == 'Ativa' ? 'Fechar' : 'Abrir'
            )
            ->icon(
                fn ($record) => $record->active == 'Ativa' ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle'
            )
            ->color(
                fn ($record) => $record->active == 'Ativa' ? 'danger' : 'success'
            )
            ->action(function (array $data, $record) {
                $record->update([
                    'active' => $record->active == 'Ativa' ? 'Inativa' : 'Ativa',
                ]);
            });
    }

   

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSchoolDiaries::route('/'),
        ];
    }
}
