<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Periods\Resources\PeriodSchoolYearResource;
use App\Filament\Clusters\Periods\Resources\utils\SchoolPermissionAccess;
use App\Filament\Resources\ClassesResource\Pages;
use App\Models\Classes;
use App\Models\Curriculum;
use App\Models\PeriodSchoolYear;
use App\Models\Role;
use App\Models\UserSchool;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;

class ClassesResource extends Resource
{
    protected static ?string $model = Classes::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Secretaria';


    public static function canAccess(): bool
    {
        $isValid = (new SchoolPermissionAccess())->canAccess();
        if (!$isValid) {
            return false;
        }

        $periodSchoolYear = PeriodSchoolYear::where('active', 'Ativa')->first();
        if (!$periodSchoolYear) {
            return false;
        }

        switch ($periodSchoolYear->type) {
            case 'Bimestral':
                if ($periodSchoolYear->bimesters->count() == 0) {
                    return false;
                }
                break;
            case 'Semestral':
                if ($periodSchoolYear->semesters->count() == 0) {
                    return false;
                }
                break;
        }

        $curriculum = Curriculum::where('school_id', SchoolResource::getSchoolId())->first();
        if (!$curriculum) {
            return false;
        }

        return true;
    }


    public static function getModelLabel(): string
    {
        return __('Classes');
    }


    public static function getSchoolId(): int
    {
        return SchoolResource::getSchoolId();
    }

    public static function getSchoolYearId(): int
    {
        return SchoolResource::getSchoolYearId();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                self::makeActiveSelect(),
                SchoolResource::makeSchoolSelect(),
                PeriodSchoolYearResource::makePeriodSchoolYearSelect(),
                CurriculumResource::makeCurriculumSelect(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->placeholder(__('Name of the class'))
                    ->maxLength(255),
                self::makeModalitySelect(),
                self::makeTurnSelect(),
                self::makeTeacherResponsibleSelect(),
                Forms\Components\TextInput::make('max_students')
                    ->required()
                    ->placeholder(__('Max students'))
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                self::makeActiveTableColumn(),
           
                Tables\Columns\TextColumn::make('curricula.series')
                    ->numeric()
                    ->label(__('Curricula'))
                    ->sortable(),
              
                Tables\Columns\TextColumn::make('modality')
                    ->searchable(),
                Tables\Columns\TextColumn::make('turn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('max_students')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacherResponsible.name')
                    
                    ->getStateUsing(
                        function (Model $record) {
                            return $record->teacherResponsible ? $record->teacherResponsible->name : 'N/A';
                        }
                    )
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


    public static function makeActiveTableColumn()
    {
        return Tables\Columns\TextColumn::make('active')
            ->label('Status')
            ->searchable()
            ->sortable()
            ->badge(fn ($record) => $record->active ? 'success' : 'danger')
            ->color(fn ($record) => $record->active === 'Ativa' ? 'success' : 'danger');
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

    private static function makeModalitySelect(): Select
    {
        return Select::make('modality')
            ->options([
                'Regular' => 'Regular',
                'EJA' => 'EJA',
                'Tecnico' => 'Tecnico',
                'Especializacao' => 'Especializacao',
                'Mestrado' => 'Mestrado',
                'Doutorado' => 'Doutorado',
            ])
            ->searchable()
            ->native(false)
            ->required()
            ->label(__('Modality'));
    }

    private static function makeTurnSelect(): Select
    {
        return Select::make('turn')
            ->options([
                'Manhã' => 'Manhã',
                'Tarde' => 'Tarde',
                'Noite' => 'Noite',
                'Integral' => 'Integral',
            ])
            ->searchable()
            ->native(false)
            ->required()
            ->label(__('Turn'));
    }

    private static function makeTeacherResponsibleSelect(): Select
    {
        return Select::make('teacher_responsible_id')
            ->options(
                UserSchool::where('school_id', self::getSchoolId())
                ->where('role_id', Role::where('name', 'teacher')->first()->id)
                ->get()
                ->pluck('user.name', 'user.id')
            )
            ->searchable()
            ->native(false)
            ->label(__('Teacher responsible'));
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageClasses::route('/'),
        ];
    }
}
