<?php

namespace App\Filament\Clusters\Periods\Resources;

use App\Filament\Clusters\Periods;
use App\Filament\Clusters\Periods\Resources\PeriodBimonthlyResource\Pages;
use App\Filament\Clusters\Periods\Resources\PeriodBimonthlyResource\RelationManagers;
use App\Filament\Resources\SchoolResource;
use App\Http\Middleware\CheckSchoolCookieForPages;
use App\Http\Middleware\RequireSchoolCookie;
use App\Models\PeriodBimonthly;
use App\Models\PeriodSchoolYear;
use App\Models\School;
use App\Models\SchoolYear;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PeriodBimonthlyResource extends Resource
{
    protected static ?string $model = PeriodBimonthly::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';


    protected static ?string $cluster = Periods::class;

    public static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;


    public static function getModelLabel(): string
    {
        return __('Bimonthly');
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

        $periodSchoolYear = PeriodSchoolYear::where('type', 'Bimestral')->where('school_year_id', self::getSchoolYearId())
        ->where('school_id',self::getSchoolId())
        ->first();

        if (!$periodSchoolYear) {
            return false;
        }

        return true;
    }

    private static function getBimesterOptions($periodSchoolYear)
    {
        if (!$periodSchoolYear) {
            return ['' => 'Selecione um período'];
        }

        $periodBimonthly = PeriodBimonthly::where('period_school_years_id', $periodSchoolYear->id)->orderBy('bimester', 'desc')->first();
        if ($periodBimonthly) {
            $bimester = $periodBimonthly->bimester;
            if ($bimester == '1º Bimestre') {
                return ['2º Bimestre' => '2º Bimestre'];
            } else if ($bimester == '2º Bimestre') {
                return ['3º Bimestre' => '3º Bimestre'];
            } else if ($bimester == '3º Bimestre') {
                return ['4º Bimestre' => '4º Bimestre'];
            } else if ($bimester == '4º Bimestre') {
                return [];
            }
        } else {
            return ['1º Bimestre' => '1º Bimestre'];
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
                            $set('bimester', null);
                        }
                    )
                    ->disabled(fn ($operation) => $operation === 'edit')
                    ->required(),

                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->after('start_date')
                    ->required(),

                Select::make('bimester')
                    ->options(
                        function (callable $get) {
                            $periodSchoolYear = PeriodSchoolYear::find($get('period_school_years_id'));
                            return self::getBimesterOptions($periodSchoolYear);
                        }
                    )
                    ->native(false)
                    ->disabled(fn ($operation) => $operation === 'edit')
                    ->required(),
            ]);
    }

    private static function queryTable()
    {
        return PeriodBimonthly::query()
            ->whereHas('periodSchoolYear', function (Builder $query) {
                $query->where('school_year_id', self::getSchoolYearId())
                    ->where(
                        'school_id',
                        self::getSchoolId()
                    );
            })
            ->withoutGlobalScope(SoftDeletingScope::class);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(self::queryTable())
            ->columns([
                Tables\Columns\TextColumn::make('id')->label(__('code')),
                Tables\Columns\TextColumn::make('active'),
                Tables\Columns\TextColumn::make('bimester'),
                Tables\Columns\TextColumn::make('periodSchoolYear.type')
                    ->label(__('period_school'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
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
            'index' => Pages\ManagePeriodBimonthlies::route('/'),
        ];
    }
}
