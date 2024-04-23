<?php

namespace App\Filament\Clusters\Periods\Resources;

use App\Filament\Clusters\Periods;
use App\Filament\Clusters\Periods\Resources\PeriodSemesterResource\Pages;
use App\Filament\Clusters\Periods\Resources\PeriodSemesterResource\RelationManagers;
use App\Models\PeriodSemester;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Pages\SubNavigationPosition;
use App\Http\Middleware\CheckSchoolCookieForPages;
use App\Http\Middleware\RequireSchoolCookie;
use App\Models\PeriodSchoolYear;
use App\Filament\Resources\SchoolResource;
use Filament\Forms\Components\Select;

class PeriodSemesterResource extends Resource
{
    protected static ?string $model = PeriodSemester::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $cluster = Periods::class;

    public static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getModelLabel(): string
    {
        return __('Semester');
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

        $isValid = (new RequireSchoolCookie())->handle(request(), function ($request) {
            return false;
        });

        if (!$isValid) {
            return false;
        }

        $isValid = (new CheckSchoolCookieForPages())->handle(request(), function ($request) {
            return false;
        });

        if (!$isValid) {
            return false;
        }

        $periodSchoolYear = PeriodSchoolYear::where('type', 'Semestral')->where('school_year_id', self::getSchoolYearId())->where(
            'school_id',
            self::getSchoolId()
        )->first();
        if (!$periodSchoolYear) {
            return false;
        }

        return true;
    }

    private static function getSemesterOptions($periodSchoolYear)
    {
        if (!$periodSchoolYear) {
            return ['' => 'Selecione um período'];
        }

        $periodSemester = PeriodSemester::where('period_school_years_id', $periodSchoolYear->id)->orderBy('semester', 'desc')->first();
        if ($periodSemester) {
            $semester = $periodSemester->semester;
            if ($semester == '1º Semestre') {
                return ['2º Semestre' => '2º Semestre'];
            } 
        } else {
            return ['1º Semestre' => '1º Semestre'];
        }
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('active')
                ->options([
                    'Ativa' => 'Ativa',
                    'Inativa' => 'Inativa',
                ])
                ->native(false)
                ->default('Ativa')
                ->label('Status'),
                Select::make('period_school_years_id')
                    ->options(PeriodSchoolYear::where('school_year_id', self::getSchoolYearId())
                        ->where('school_id', self::getSchoolId())
                        ->where('school_year_id', self::getSchoolYearId())
                        ->get()
                        ->pluck('type', 'id'))
                    ->native(false)
                    ->reactive()
                    ->afterStateUpdated(
                        function (callable $set) {
                            $set('semester', null);
                        }
                    )
                ->disabled(fn ($operation) => $operation === 'edit')
                ->required(),

                Forms\Components\DatePicker::make('start_date')
                ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->after('start_date')
                    ->required(),

                Select::make('semester')
                    ->options(
                        function (callable $get) {
                            $periodSchoolYear = PeriodSchoolYear::find($get('period_school_years_id'));
                            return self::getSemesterOptions($periodSchoolYear);
                        }
                    )
                    ->native(false)
                    ->disabled(fn ($operation) => $operation === 'edit')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('active'),
                Tables\Columns\TextColumn::make('periodSchoolYear.type')
                ->label(__('period_school'))
                ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('semester')
                    ->searchable(),
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
            'index' => Pages\ManagePeriodSemesters::route('/'),
        ];
    }
}
