<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolDiarResource\Pages;
use App\Models\City;
use App\Models\School;
use App\Models\SchoolDiar;
use App\Models\State;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SchoolDiarResource extends Resource
{
    protected static ?string $model = SchoolDiar::class;

    protected static ?string $navigationGroup  = 'Secretaria';

    protected static ?string $navigationIcon = 'heroicon-o-calendar';


    public static function getModelLabel(): string
    {
        return __('Diário Escola');
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

                Forms\Components\Select::make('state_school')
                    ->options(State::all()->pluck('name', 'id'))
                    ->searchable()
                    ->reactive()
                    ->getOptionLabelUsing(fn ($value) => State::find($value)->name)
                    ->afterStateUpdated(fn (callable $set) => $set('city_id', null))
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),

                Forms\Components\Select::make('city_school')
                    ->options(function (callable $get) {
                        $state = State::find($get('state_school'));
                        return $state ? $state->cities->pluck('name', 'id') : ['' => 'Selecione um estado'];
                    })
                    ->getOptionLabelUsing(function ($value) {
                        return City::find($value)->name;
                    })
                    ->reactive()
                    ->searchable()
                    ->required(fn (string $operation): bool => $operation === 'create'),

                Select::make('school_id')
                    ->options(
                        function (callable $get) {
                            $city = City::find($get('city_school'));
                            if ($city) {
                                if ($city->schools->count() == 0) {
                                    return ['' => 'Nenhuma escola encontrada'];
                                }
                                return $city->schools->filter(function ($school) {
                                    return auth()->user()->hasPermissionForSchool('create SchoolDiar', $school->code);
                                })->pluck('name', 'id');
                            }
                            return ['' => 'Selecione uma cidade'];
                        }
                    )
                    ->getOptionLabelUsing(function ($value) {
                        return School::find($value)->name;
                    })
                    ->required()
                    ->searchable()
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->placeholder(__('Selecione uma escola')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('active'),
                Tables\Columns\TextColumn::make('school.name')
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
            'index' => Pages\ManageSchoolDiars::route('/'),
        ];
    }
}
