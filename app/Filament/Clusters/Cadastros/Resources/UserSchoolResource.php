<?php

namespace App\Filament\Clusters\Cadastros\Resources;

use App\Filament\Clusters\Cadastros;
use App\Filament\Clusters\Cadastros\Resources\UserSchoolResource\Pages;
use App\Models\City;
use App\Models\State;
use App\Models\UserSchool;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserSchoolResource extends Resource
{
    protected static ?string $model = UserSchool::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    public static function getModelLabel(): string
    {
        return __('Vinculo');
    }

    protected static ?string $cluster = Cadastros::class;

    public static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

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
                    ->default('Ativa'),

                Select::make('role_id')
                    ->options(fn () => \App\Models\Role::all()->pluck('role', 'id')->toArray())
                    ->required()
                    ->searchable()
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->placeholder(__('Selecione um cargo')),

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
                    ->required(),

                Select::make('school_id')
                    ->options(
                        function (callable $get) {
                            $city = City::find($get('city_school'));
                            if ($city) {
                                if ($city->schools->count() == 0) {
                                    return ['' => 'Nenhuma escola encontrada'];
                                }
                                return $city->schools->pluck('name', 'id');
                            }
                            return ['' => 'Selecione uma cidade'];
                        }
                    )
                    ->required()
                    ->searchable()
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->placeholder(__('Selecione uma escola')),
                Select::make('user_id')
                    ->options(fn () => \App\Models\User::all()->pluck('name', 'id')->toArray())
                    ->required()
                    ->searchable()
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->placeholder(__('Selecione um usuário')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('active')
                    ->label('Status')
                    ->searchable()
                    ->sortable()
                    ->badge(fn ($record) => $record->active ? 'success' : 'danger')
                    ->color(fn ($record) => $record->active === 'Ativa' ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('role_school')
                    ->getStateUsing(
                        function (Model $record) {
                            return $record->role->getRoleAttribute();
                        }
                    )
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('school.name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('school.code')
                    ->label('Código')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            'index' => Pages\ManageUserSchools::route('/'),
        ];
    }
}
