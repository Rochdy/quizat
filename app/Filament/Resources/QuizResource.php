<?php

namespace App\Filament\Resources;

use App\Filament\Quiz\Pages\AnswerQuiz;
use App\Filament\Resources\QuizResource\Pages;
use App\Filament\Resources\QuizResource\RelationManagers;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Quiz Info')
                        ->icon('heroicon-m-information-circle')
                        ->schema([
                            //                            Forms\Components\Select::make('user_id')
                            //                                ->relationship('user', 'name')
                            //                                ->hidden()
                            //                                ->default(auth()->id()),
                            Forms\Components\TextInput::make('title')
                                ->label('Quiz Title')
                                ->required()->columnSpanFull(),
                            Forms\Components\RichEditor::make('notes')
                                ->label('Quiz Notes')
                                ->columnSpanFull()
                                ->toolbarButtons([
                                    'bold',
                                    'italic',
                                    'link',
                                    'orderedList',
                                    'strike',
                                    'underline',
                                ]),
                            Forms\Components\Toggle::make('is_available')->label('Is Available')->default(false)->reactive(),

                            Forms\Components\DateTimePicker::make('starts_at')
                                ->label('Start Date')
                                ->visible(fn (callable $get) => $get('is_available') === true)
                                ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Choose a start date if you want this quiz to be available after this date')
                                ->columns(2),
                            Forms\Components\DateTimePicker::make('expires_at')
                                ->label('End Date')
                                ->minDate(fn (callable $get) => $get('starts_at'))
                                ->visible(fn (callable $get) => $get('is_available') === true)
                                ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Choose an expiration date if you want this quiz to be available before this date')
                                ->columns(2),

                            //                Forms\Components\TextInput::make('slug')
                            //                    ->required(),
                        ])->columns(3),
                    Forms\Components\Wizard\Step::make('Quiz Questions')
                        ->icon('heroicon-m-list-bullet')
                        ->schema([
                            Forms\Components\Repeater::make('questions')
                                ->relationship()
                                ->schema([
                                    Forms\Components\RichEditor::make('question_text')->required()->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'strike',
                                        'underline',
                                    ]),
                                    Forms\Components\Repeater::make('answers')
                                        ->relationship()
                                        ->schema([
                                            Forms\Components\TextInput::make('answer_text')->required(),
                                            Forms\Components\Toggle::make('is_correct')->label('Is correct')->distinct()
                                                ->validationMessages([
                                                    'distinct' => 'The question must 1 correct answer',
                                                ]),
                                        ])->required()->collapsible()->columns(1)->minItems(2)
                                        ->validationMessages([
                                            'min' => 'The question must have more than 2 answers',
                                        ])
                                        ->orderColumn('order')->reorderableWithButtons(),
                                ])->columns(2)->orderColumn('order')->reorderableWithButtons(),

                        ]),

                    // ->orderColumn('sort')
                ])->skippable()
                    // ->columns(1)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //                Tables\Columns\TextColumn::make('user.name')
                //                    ->numeric()
                //                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->limit(10)
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_available')
                    ->sortable(),
                //                Tables\Columns\TextColumn::make('questions_count')
                //                    ->counts('questions')
                //                    ->label('Total Qs')
                //                ,
                //                Tables\Columns\TextColumn::make('attempts_count')
                //                    ->counts('attempts')
                //                    ->label('Total As')
                // ->formatStateUsing(fn ($record) => $record->attempts()->count() === 0 ? 'No one answered yet' : $record->attempts()->count())
                // ,

                //                Tables\Columns\TextColumn::make('starts_at')
                //                    ->dateTime()
                //                    ->sortable(),
                //                Tables\Columns\TextColumn::make('expires_at')
                //                    ->dateTime()
                //                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Quiz Link')
                    ->formatStateUsing(fn ($state) => 'Visit Quiz')
                    ->badge()
                    ->color('gray')
                    ->icon('heroicon-m-arrow-up-right')
                    ->url(fn (Quiz $record): string => AnswerQuiz::getUrl(
                        ['quizSlug' => $record->slug],
                        true,
                        'quiz'
                    ))
                    ->openUrlInNewTab(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make(''),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('attempts')->where('user_id', Auth::id());
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AttemptsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
            'view' => Pages\viewQuizDetails::route('/{record}/details'),

        ];
    }
}
