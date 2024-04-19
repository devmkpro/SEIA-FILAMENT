<?php

namespace App\Filament\Pages;


use Illuminate\Support\Facades\Cookie;
use Filament\Tables\Actions\Action;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Support\Facades\Redirect;

class MySchools extends Page implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static string $view = 'filament.pages.my-schools';

    public static ?string $title = 'Minhas Escolas';

    public function tableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        if (request()->user()->isAdmin()) {
            return \App\Models\School::query();
        }

        return \App\Models\School::query()->whereHas('users', function ($query) {
            $query->where('user_id', request()->user()->id);
        });
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo('select-my School');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->tableQuery())
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('city.state.name')
                    ->toggleable(),

                TextColumn::make('city.name')
                    ->label('Cidade')
                    ->searchable(),

            ])
            ->filters([
                //
            ])
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

                            Redirect::to('/admin');
                        }
                    }),
            ], position: ActionsPosition::BeforeColumns);
    }
}
