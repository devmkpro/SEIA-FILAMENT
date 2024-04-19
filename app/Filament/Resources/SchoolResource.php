<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolResource\Pages;
use App\Models\School;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\City;
use App\Models\State;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Support\Facades\Cookie;
use Filament\Tables\Actions\Action;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;

    public static function getModelLabel(): string
    {
        return __('Escola');
    }

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Administrativo';

    public static function getEloquentQuery(): Builder
    {

        if (auth()->user()->isAdmin()) {
            return static::getModel()::query();
        }

        return static::getModel()::query()->whereHas('users', function ($query) {
            $query->where('user_id', auth()->id());
        });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    Section::make(
                        [
                            self::makeActiveSelect(),
                            self::makeTypeSelect(),
                            self::makeCategorySelect(),
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(200),
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(200),
                            Forms\Components\TextInput::make('address')
                                ->required()
                                ->maxLength(200),
                            Forms\Components\TextInput::make('zip_code')
                                ->required()
                                ->mask('99999-999')
                                ->maxLength(20),
                            Forms\Components\TextInput::make('phone')
                                ->tel()
                                ->required()
                                ->maxLength(20)
                                ->mask('(99) 99999-9999'),
                            Forms\Components\TextInput::make('neighborhood')
                                ->maxLength(200),
                            Forms\Components\TextInput::make('landline')
                                ->tel()
                                ->mask('(99) 9999-9999')
                                ->maxLength(200),
                            Forms\Components\TextInput::make('cnpj')
                                ->mask('99.999.999/9999-99'),
                            Forms\Components\TextInput::make('complement')
                                ->maxLength(200),
                            Forms\Components\TextInput::make('acronym')
                                ->maxLength(10)
                                ->columnSpan(2),
                        ]
                    )->columnSpan(2)
                        ->icon('heroicon-o-academic-cap')
                        ->description('Informações Gerais'),
                    self::makeLocationSection(),
                ]
            )->columns(3);
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

    private static function makeTypeSelect(): Select
    {
        return Select::make('type')
            ->options([
                'Municipal' => 'Municipal',
                'Estadual' => 'Estadual',
                'Federal' => 'Federal',
                'Privada' => 'Privada',
            ])
            ->label('Tipo de Escola')
            ->searchable()
            ->required();
    }

    private static function makeCategorySelect(): Select
    {
        return Select::make('category')
            ->options([
                'Creche' => 'Creche',
                'Pré-Escola' => 'Pré-Escola',
                'Fundamental' => 'Fundamental',
                'Médio' => 'Médio',
                'Superior' => 'Superior',
            ])
            ->searchable()
            ->required();
    }

    private static function makeLocationSection(): Section
    {
        return Section::make([
            Forms\Components\Select::make('state_id')
                ->options(State::all()->pluck('name', 'id'))
                ->searchable()
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('city_id', null))
                ->dehydrated(fn (?string $state) => filled($state))
                ->required(fn (string $operation): bool => $operation === 'create'),

            Forms\Components\Select::make('city_id')
                ->options(function (callable $get) {
                    $state = State::find($get('state_id'));
                    return $state ? $state->cities->pluck('name', 'id') : ['' => 'Selecione um estado'];
                })
                ->getOptionLabelUsing(function ($value) {
                    return City::find($value)->name;
                })
                ->searchable()
                ->required(),
        ])
            ->columnSpan(1)
            ->icon('heroicon-o-globe-americas')
            ->description('Localização');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(
                [
                    Tables\Columns\TextColumn::make('code')
                        ->sortable()
                        ->searchable(),
                    Tables\Columns\TextColumn::make('active')
                        ->label('Status')
                        ->searchable()
                        ->sortable()
                        ->badge(fn ($record) => $record->active ? 'success' : 'danger')
                        ->color(fn ($record) => $record->active === 'Ativa' ? 'success' : 'danger'),

                    Tables\Columns\TextColumn::make('name')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('email')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('address')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('phone')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('neighborhood')
                        ->searchable(),


                    Tables\Columns\TextColumn::make('city.state.name')
                        ->toggleable(isToggledHiddenByDefault: true),

                    Tables\Columns\TextColumn::make('city.name')
                        ->label('Cidade')
                        ->searchable(),


                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime('d/m/Y')
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ]
            )->filters([])
            ->actions([
                Action::make('select')
                    ->label(__('Gerenciar'))
                    ->icon('heroicon-o-academic-cap')
                    ->color('warning')
                    ->action(function ($record) {
                        if (request()->user()->isAdmin() || request()->user()->schools()->where('school_id', $record->id)->exists()) {
                            Cookie::queue('SHID', $record->code, 60 * 24 * 30);
                            Notification::make()
                                ->title("{$record->name}")
                                ->body("Você já pode gerenciar a escola!")
                                ->icon("heroicon-o-check-circle")
                                ->color("success")
                                ->send();
                        }
                    }),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSchools::route('/'),
            'create' => Pages\CreateSchool::route('/create'),
            'edit' => Pages\EditSchool::route('/{record}/edit'),
        ];
    }
}
