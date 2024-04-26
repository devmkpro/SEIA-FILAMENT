<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CurriculumResource\Pages;
use App\Http\Middleware\CheckSchoolCookieForPages;
use App\Http\Middleware\RequireSchoolCookie;
use App\Models\Curriculum;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;


class CurriculumResource extends Resource
{
    protected static ?string $model = Curriculum::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationGroup = 'Secretaria';

    public static function getSchoolId(): int
    {
        return SchoolResource::getSchoolId();
    }

    public static function getSchoolYearId(): int
    {
        return SchoolResource::getSchoolYearId();
    }

    public static function getModelLabel(): string
    {
        return __('Curriculum');
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

        return $isValid;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Select::make('school_id')
                    ->options(fn () => \App\Models\School::where('id', self::getSchoolId())->pluck('name', 'id')->toArray())
                    ->required()
                    ->searchable()
                    ->default(self::getSchoolId())
                    ->placeholder(__('Select a school')),

                Select::make('series')
                    ->options([
                        'educacao_infantil' => 'Educação Infantil',
                        'fundamental_i' => 'Fundamental I',
                        'fundamental_ii' => 'Fundamental II',
                        'ensino_medio' => 'Ensino Médio',
                        'eja' => 'EJA',
                        'tecnico' => 'Técnico',
                        'other' => 'Outro',
                    ])
                    ->required()
                    ->searchable()
                    ->placeholder(__('Select a series')),

                Forms\Components\TextInput::make('weekly_hours')
                    ->required()
                    ->placeholder(__('Type the weekly hours'))
                    ->numeric(),

                Forms\Components\TextInput::make('total_hours')
                    ->required()
                    ->placeholder(__('Type the total hours'))
                    ->numeric(),

                Forms\Components\TimePicker::make('start_time')
                    ->required(),
                Forms\Components\TimePicker::make('end_time')
                    ->required(),


                Select::make('modality')
                    ->options([
                        'bercario' => 'Berçário',
                        'creche' => 'Creche',
                        'pre_escola' => 'Pré-Escola',
                        'fundamental' => 'Fundamental',
                        'medio' => 'Médio',
                        'eja' => 'EJA',
                        'educacao_especial' => 'Educação Especial',
                        'tecnico' => 'Técnico',
                        'other' => 'Outro',
                    ])
                    ->required()
                    ->searchable()
                    ->placeholder(__('Select a modality')),

                Forms\Components\TextInput::make('default_time_class')
                    ->required()
                    ->placeholder(__('Type the default time class'))
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('school.name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('series')
                    ->searchable(),

                Tables\Columns\TextColumn::make('modality')
                    ->searchable(),

                Tables\Columns\TextColumn::make('weekly_hours')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_hours')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('end_time'),

                Tables\Columns\TextColumn::make('default_time_class')
                    ->numeric()
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
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCurricula::route('/'),
        ];
    }
}
