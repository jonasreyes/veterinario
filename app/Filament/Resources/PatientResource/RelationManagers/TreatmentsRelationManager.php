<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\PatientResource\RelationManagers;

class TreatmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'treatments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                ->label('Descripción')
                ->required()
                ->maxLength(255)
                ->columnSpan('full'),

                Forms\Components\Textarea::make('notes')
                ->label('Notas')
                ->maxLength(65535)
                ->columnSpan('full'),

                Forms\Components\TextInput::make('price')
                ->label('Precio')
                ->numeric()
                ->prefix('Bs')
                ->maxValue(42949672.95),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Historial de Tratamientos')
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('description')
                ->label('Descripción'),

                Tables\Columns\TextColumn::make('price')
                ->label('Precio')
                ->money('VES')
                ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Creado')
                ->dateTime('d-m-Y h:i A')
                ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                ->options([
                    'gato' => 'Gato',
                    'perro' => 'Perro',
                    'conejo' => 'Conejo',
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Nuevo tratamiento'),
            ])
            ->actions([
                ViewAction::make()
                ->label('Ver'),
                Tables\Actions\EditAction::make()
                ->label('Editar'),
                Tables\Actions\DeleteAction::make()
                ->label('Eliminar'),

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
}
