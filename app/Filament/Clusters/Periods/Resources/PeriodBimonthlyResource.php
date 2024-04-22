<?php

namespace App\Filament\Clusters\Periods\Resources;

use App\Filament\Clusters\Periods;
use App\Filament\Clusters\Periods\Resources\PeriodBimonthlyResource\Pages;
use App\Filament\Clusters\Periods\Resources\PeriodBimonthlyResource\RelationManagers;
use App\Models\PeriodBimonthly;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PeriodBimonthlyResource extends Resource
{
    protected static ?string $model = PeriodBimonthly::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Periods::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('active')
                    ->required(),
                Forms\Components\TextInput::make('period_school_years_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->required(),
                Forms\Components\TextInput::make('bimester')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('active'),
                Tables\Columns\TextColumn::make('period_school_years_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bimester'),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeriodBimonthlies::route('/'),
            'create' => Pages\CreatePeriodBimonthly::route('/create'),
            'edit' => Pages\EditPeriodBimonthly::route('/{record}/edit'),
        ];
    }
}
