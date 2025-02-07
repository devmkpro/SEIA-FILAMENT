<?php

namespace App\Filament\Clusters\Regioes\Resources;

use App\Filament\Clusters\Regioes;
use App\Filament\Clusters\Regioes\Resources\CityResource\Pages;
use App\Filament\Clusters\Regioes\Resources\CityResource\RelationManagers;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    public static function getModelLabel(): string
    {
        return __('Cidade');
    }

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $cluster = Regioes::class;

    public static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ibge_code')
                    ->required()
                    ->unique('cities', 'ibge_code', ignoreRecord: true)
                    ->numeric(),

                Select::make('state_id')
                    ->relationship('state', 'name')
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ibge_code')
                    ->sortable(),

                Tables\Columns\TextColumn::make('state.name')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(
                        function ($data, $record) {
                            if ($record->schools->count() > 0) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('Não é possível excluir'))
                                    ->body(__('Existem escolas vinculadas'))
                                    ->send();

                                return;
                            }

                            Notification::make()
                                ->success()
                                ->title(__('Cidade excluída'))
                                ->send();

                            $record->delete();
                        }
                    )
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
            'index' => Pages\ManageCities::route('/'),
        ];
    }
}
