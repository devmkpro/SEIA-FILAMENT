<?php

namespace App\Filament\Clusters\Regioes\Resources;

use App\Filament\Clusters\Regioes;
use App\Filament\Clusters\Regioes\Resources\StateResource\Pages;
use App\Filament\Clusters\Regioes\Resources\StateResource\RelationManagers;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StateResource extends Resource
{
    protected static ?string $model = State::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    public static function getModelLabel(): string
    {
        return __('Estado');
    }

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
                    ->unique('states', 'ibge_code', ignoreRecord: true)
                    ->numeric(),
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(
                        function ($data, $record) {
                            if ($record->cities->count() > 0) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('Não é possível excluir'))
                                    ->body(__('Existem cidades vinculadas'))
                                    ->send();

                                return;
                            }

                            $record->delete();

                            Notification::make()
                                ->success()
                                ->title(__('Estado excluído'))
                                ->send();
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
            'index' => Pages\ManageStates::route('/'),
        ];
    }
}
