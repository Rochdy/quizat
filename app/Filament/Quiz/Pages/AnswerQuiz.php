<?php

namespace App\Filament\Quiz\Pages;

use App\Jobs\ProcessQuizAttempt;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Validation\Rule;

class AnswerQuiz extends Page implements HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?array $data = [];

    public Quiz $quiz;

    public string $quizSlug;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.quiz.pages.answer-quiz';

    protected static ?string $slug = '/{quizSlug}';

    protected static ?string $title = '';

    protected static bool $shouldRegisterNavigation = false;

    public function mount(): void
    {
        $this->quiz = Quiz::with('questions.answers')
            ->whereSlug($this->quizSlug)
            ->currentlyAvailable()
            ->firstOrFail();
        $this->form->fill(); // optional if default values
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Placeholder::make('quiz_title')
                ->hiddenLabel(true)
                ->content(fn () => 'Quiz: '.$this->quiz->title)
                ->extraAttributes(['class' => 'text-center text-xl font-bold']),

            Placeholder::make('quiz_owner')
                ->hiddenLabel(true)
                ->content(fn () => 'By: '.$this->quiz->user->name)
                ->extraAttributes(['class' => 'text-center font-bold']),

            Placeholder::make('quiz_notes')
                ->hiddenLabel(true)
                ->content(fn () => new \Illuminate\Support\HtmlString($this->quiz->notes))
                ->extraAttributes(['class' => 'text-center']),
            // ->columnSpanFull()

            Grid::make(2)->schema([
                TextInput::make('student_name')->required(),
                TextInput::make('student_identifier')->required()->rules(function () {
                    return [
                        Rule::unique('quiz_attempts', 'student_identifier')
                            ->where(function ($query) {
                                return $query->where('quiz_id', $this->quiz->id);
                            }),
                    ];
                })->validationMessages([
                    'unique' => 'This student already answered this quiz.',
                ]),
            ]),

            ...$this->quiz->questions->map(function ($question) {
                return Fieldset::make('')
                    ->label('')
                    ->schema([
                        Placeholder::make("question_{$question->id}_text")
                            ->hiddenLabel(true)
                            ->content(new \Illuminate\Support\HtmlString($question->question_text)),

                        CheckboxList::make("answers.{$question->id}")
                            ->hiddenLabel(true)
                            ->options($question->answers->pluck('answer_text', 'id'))
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set) use ($question) {
                                // If more than one checkbox is checked, keep only the last one
                                if (is_array($state) && count($state) > 1) {
                                    $set("answers.{$question->id}", [array_slice($state, -1)[0]]);
                                }
                            })
                            ->validationMessages([
                                'required' => "You Can't leave this question without answering.",
                            ])
                            ->columns(2),
                    ]);
            })->toArray(),
        ])->statePath('data');
    }

    //    protected function getFormSchema(): array
    //    {
    //        return [
    //            Placeholder::make('quiz_title')
    //                ->hiddenLabel(true)
    //                ->content(fn () => 'Quiz: '.$this->quiz->title)
    //                ->extraAttributes(['class' => 'text-center text-xl font-bold']),
    //
    //            Placeholder::make('quiz_owner')
    //                ->hiddenLabel(true)
    //                ->content(fn () => 'By: '.$this->quiz->user->name)
    //                ->extraAttributes(['class' => 'text-center text-xl font-bold']),
    //
    //            Placeholder::make('quiz_notes')
    //                ->hiddenLabel(true)
    //                ->content(fn () => new \Illuminate\Support\HtmlString($this->quiz->notes))
    //                ->extraAttributes(['class' => 'text-center']),
    //                //->columnSpanFull()
    //
    //            Grid::make(2)->schema([
    //                TextInput::make('student_name')->required(),
    //                TextInput::make('student_identifier')->required(),
    //            ]),
    //
    //            ...$this->quiz->questions->map(function ($question) {
    //                return Fieldset::make('')
    //                    ->label('')
    //                    ->schema([
    //                        \Filament\Forms\Components\Placeholder::make("question_{$question->id}_text")
    //                            ->hiddenLabel(true)
    //                            ->content(new \Illuminate\Support\HtmlString($question->question_text)),
    //
    //                        CheckboxList::make("answers.{$question->id}")
    //                            ->hiddenLabel(true)
    //                            ->options($question->answers->pluck('answer_text', 'id'))
    //                            ->columns(2),
    //                    ]);
    //            })->toArray()
    //        ];
    //    }

    public function submit()
    {

        $data = $this->form->getState();

        $attempt = QuizAttempt::create([
            'quiz_id' => $this->quiz->id,
            'student_name' => $data['student_name'],
            'student_identifier' => $data['student_identifier'],
        ]);

        foreach ($data['answers'] as $questionId => $answerId) {
            QuizAttemptAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $questionId,
                'answer_id' => $answerId[0],
            ]);
        }

        ProcessQuizAttempt::dispatch($attempt);
        // $this->render('thanks');

        $this->redirect('/thanks');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('submitAnswers'),
        ];
    }

    public function submitForm(): void
    {
        dd(33);
    }

    public function performDangerousAction(): void
    {
        dd(3333);

    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('submitAnswers')
                ->button()
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Are you sure you want to submit your answers?')
                ->modalDescription('This action cannot be undone.')
                ->modal(function () {
                    try {
                        $this->form->getState();

                        return true;
                    } catch (\Exception $e) {
                        return false;
                    }
                })
                ->action(fn () => $this->form->validate() && $this->submit()),
        ];
    }
}
