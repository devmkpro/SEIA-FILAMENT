<?php

namespace App\Filament\Clusters\Periods\Resources;

use App\Filament\Clusters\Periodos;
use App\Filament\Clusters\Periods\Resources\PeriodSchoolYearResource\Pages;
use App\Models\PeriodSchoolYear;
use App\Models\School;
use App\Models\SchoolYear;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Redirect;

class PeriodSchoolYearResource extends Resource
{
    protected static ?string $cluster = Periodos::class;

    protected static ?string $model = PeriodSchoolYear::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getModelLabel(): string
    {
        return __('Período');
    }

    public static function canAccess(): bool
    {
        $anoLetivo = SchoolYear::where('active', 'Ativa')->first();
        if (!$anoLetivo) {
            return false;
        }

        return true;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                self::makeActiveSelect(),
                self::makeSchoolYearSelect(),
                self::makeSchoolSelect(),
                self::makeTypeSelect(),
            ]);
    }

    private static function makeActiveSelect(): Select
    {
        return Select::make('active')
            ->options([
                'Ativa' => 'Ativa',
                'Inativa' => 'Inativa',
            ])
            ->native(false)
            ->default(self::getDefaultActiveValue())
            ->label('Status')
            ->rules([
                self::validateActive(),
            ]);
    }

    private static function getDefaultActiveValue(): string
    {
        $schoolId = School::where('code', request()->cookie('SHID'))->first()->id;
        return PeriodSchoolYear::where('active', 'Ativa')
            ->where('school_id', $schoolId)
            ->exists() ? 'Inativa' : 'Ativa';
    }

    private static function validateActive(): Closure
    {
        return function () {
            return function (string $attribute, $value, Closure $fail) {
                $school = School::where('code', request()->cookie('SHID'))->first();
                if (PeriodSchoolYear::where('school_year_id', $school->id)
                    ->where('school_id', $school->id)
                    ->where('active', 'Ativa')
                    ->exists()
                ) {
                    $fail('Já existe um período ativo!');
                }
            };
        };
    }

    private static function makeSchoolYearSelect(): Select
    {
        return Select::make('school_year_id')
            ->options(
                SchoolYear::where('active', 'Ativa')->get()->pluck('school_year', 'id')
            )
            ->label('Ano Letivo')
            ->default(SchoolYear::where('active', 'Ativa')->first()->id)
            ->required();
    }

    private static function makeSchoolSelect(): Select
    {
        return Select::make('school_id')
            ->options(
                School::where('code', request()->cookie('SHID'))->get()->pluck('name', 'id')
            )
            ->label('Escola')
            ->default(School::where('code', request()->cookie('SHID'))->first()->id)
            ->required();
    }

    private static function makeTypeSelect(): Select
    {
        return Select::make('type')
            ->options([
                'Bimestral' => 'Bimestral',
                'Semestral' => 'Semestral',
            ])
            ->native(false)
            ->required()
            ->label('Tipo');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('active'),
                Tables\Columns\TextColumn::make('schoolYear.school_year')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePeriodSchoolYears::route('/'),
        ];
    }
}
