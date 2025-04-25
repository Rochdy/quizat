<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizAttemptAnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuizAttemptAnswer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quiz_attempt_id' => QuizAttempt::factory(),
            'question_id' => Question::factory(),
            'answer_id' => Answer::factory(),
        ];
    }
}
