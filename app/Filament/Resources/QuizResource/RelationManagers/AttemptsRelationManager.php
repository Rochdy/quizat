<?php

namespace App\Filament\Resources\QuizResource\RelationManagers;

use App\Filament\Exports\QuizAttemptExporter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Table;

class AttemptsRelationManager extends RelationManager
{
    protected static string $relationship = 'attempts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('student_name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('student_name')
            ->columns([
                Tables\Columns\TextColumn::make('student_name')->searchable(),
                Tables\Columns\TextColumn::make('student_identifier')->searchable(),
                Tables\Columns\TextColumn::make('score')->label('Score')
                    ->formatStateUsing(function ($state) {
                        return $state.'%';
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('Date')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                // Tables\Actions\ExportAction::make('download')
                ExportAction::make()
                    ->exporter(QuizAttemptExporter::class)
                    ->label('Export')
                    ->color('primary'),
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
}
