<?php

namespace App\Filament\Clusters\Cadastros\Resources;

use App\Filament\Clusters\Cadastros;
use App\Filament\Clusters\Cadastros\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $cluster = Cadastros::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function getModelLabel(): string
    {
        return __('Usuário');
    }


    public static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true, table: 'users', column: 'email')
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->maxLength(255)
                    ->confirmed(),

                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->requiredWith('password')
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255),

                Select::make('role_id')
                    ->relationship('roles', 'name')
                    ->options(fn () => \App\Models\Role::all()->pluck('role', 'id')->toArray())
                    ->required()
                    ->searchable()
                    ->preload()
                    ->placeholder(__('Selecione um cargo'))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('primary_role')
                    ->getStateUsing(
                        function (Model $record) {
                            return $record->roles->first()->getRoleAttribute();
                        }
                    )
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(format: 'd/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
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
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
