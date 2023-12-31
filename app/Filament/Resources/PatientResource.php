<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'Pacientes';

    protected static ?string $pageLabel = 'Pacientes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->maxLength(255),

                Forms\Components\Select::make('type')
                ->label('Tipo de mascota')
                ->options([
                    'gato' => 'Gato',
                    'perro' => 'Perro',
                    'conejo' => 'Conejo',
                ])->required(),

                Forms\Components\DatePicker::make('date_of_birth')
                ->label('Fecha de nacimiento')
                ->required()
                ->maxDate(now()),

                Forms\Components\Select::make('owner_id')
                ->label('Propietario')
                ->relationship('owner', 'name')
                ->searchable()
                ->preload()
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                    ->label('Nombre del Propietario a Crear')
                    ->required()
                    ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->email()
                    ->required()
                    ->maxLength(255),

                    Forms\Components\TextInput::make('phone')
                    ->label('Nro. Teléfono')
                    ->tel()
                    ->required(),
                ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Retorna los nombres de las comumnas o campos de la BD
                Tables\Columns\TextColumn::make('name')
                ->label('Nombre')
                ->searchable(),
                Tables\Columns\TextColumn::make('type')
                ->label('Tipo')
                ,
                Tables\Columns\TextColumn::make('date_of_birth')
                ->label('Fecha de Nacimiento')
                ->sortable(),
                Tables\Columns\TextColumn::make('owner.name')
                ->label('Nombre de Propietario')
                ->searchable(),
            ])
            ->filters([
                // Filtro de los datos de las migraciones
                Tables\Filters\SelectFilter::make('type')
                ->label('Tipo')
                ->options([
                    'gato' => 'Gato',
                    'perro' => 'Perro',
                    'conejo' => 'Conejo',
                ]),
            ])
            ->actions([
                ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            RelationManagers\TreatmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }

}
