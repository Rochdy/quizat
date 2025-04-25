<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Answer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'question_id' => Question::factory(),
            'answer_text' => fake()->text(),
            'is_correct' => fake()->boolean(),
        ];
    }
}
