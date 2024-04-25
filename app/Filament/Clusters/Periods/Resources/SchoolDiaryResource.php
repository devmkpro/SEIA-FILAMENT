<?php

namespace App\Filament\Clusters\Periods\Resources;

use App\Filament\Clusters\Periods;
use App\Filament\Clusters\Periods\Resources\SchoolDiaryResource\Pages;
use App\Filament\Clusters\Periods\Resources\utils\SchoolPermissionAccess;
use App\Filament\Resources\SchoolResource;
use App\Models\PeriodSchoolYear;
use App\Models\SchoolDiary;
use App\Models\SchoolYear;
use Closure;
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

        if (!$periodSchoolYear) {
            return false;
        }

        return true;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                self::makeActiveSelect(),
                self::makeSchoolIdSelectable(),
                self::makePeriodSchoolYearsIdSelectable(),

            ]);
    }

    private static function validateActive(): Closure
    {
        return function () {
            return function (string $attribute, $value, Closure $fail) {
                $schoolYear = SchoolYear::find(self::getSchoolYearId());
                
                if (!$schoolYear->periods()->where('active', 'Ativa')
                ->where('id', $value)
                ->first()) {
                    $fail(__('Este período do ano não está aberto.'));
                } else if (SchoolDiary::where('school_id', self::getSchoolId())
                    ->where('period_school_years_id', $value)
                    ->where('active', 'Ativa')
                    ->exists()
                ) {
                    $fail(__('Já existe um diário aberto para este período do ano.'));
                }
            };
        };
    }

    private static function hasRelationships($record): bool
    {
        return $record->periodSchoolYear->active == 'Ativa';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SchoolResource::makeActiveTableColumn(),
                Tables\Columns\TextColumn::make('school.name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('periodSchoolYear.type')
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
                Tables\Actions\DeleteAction::make()
                ->visible(
                    function ($record) {
                        return $record->active == 'Inativa' && !self::hasRelationships($record);
                    }
                )
                ->action(
                    function ($data, $record) {
                        if (self::hasRelationships($record)) {
                            Notification::make()
                                ->danger()
                                ->title(__('Não é possível excluir'))
                                ->body(__('Existem vínculos cadastrados'))
                                ->send();

                            return;
                        }

                        $record->delete();

                        Notification::make()
                            ->success()
                            ->title(__('Período excluído'))
                            ->send();

                        Redirect::back();
                    }
                ),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([]);
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

    private static function makeActiveSelect(): Select
    {
        return Select::make('active')
            ->options([
                'Ativa' => 'Ativa',
                'Inativa' => 'Inativa',
            ])
            ->native(false)
            ->default('Ativa')
            ->label('Status');
    }

    private static function makeSchoolIdSelectable(): Select
    {
        return Select::make('school_id')
            ->options(
                \App\Models\School::where('id', self::getSchoolId())->pluck('name', 'id')
            )
            ->default(self::getSchoolId())
            ->searchable()
            ->required();
    }

    private static function makePeriodSchoolYearsIdSelectable(): Select
    {
        return Select::make('period_school_years_id')
            ->options(
                \App\Models\PeriodSchoolYear::where('school_year_id', self::getSchoolYearId())->pluck('type', 'id')
            )
            ->searchable()
            ->required()
            ->rules([
                self::validateActive(),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSchoolDiaries::route('/'),
        ];
    }
}
