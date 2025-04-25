<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $user = User::create([
            'name' => 'Daniel Craig',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
        ]);

        // 2. Create a quiz
        $quiz = Quiz::create([
            'title' => 'History 01',
            'notes' => 'Good Luck',
            'user_id' => $user->id,
            'is_available' => true,
        ]);

        $questions = [
            [
                'question' => 'Who was the first President of the United States?',
                'answers' => [
                    'George Washington' => true,
                    'Abraham Lincoln' => false,
                    'Thomas Jefferson' => false,
                    'John Adams' => false,
                ],
            ],
            [
                'question' => 'In which year did World War II end?',
                'answers' => [
                    '1945' => true,
                    '1939' => false,
                    '1918' => false,
                    '1965' => false,
                ],
            ],
            [
                'question' => 'Which ancient civilization built the pyramids?',
                'answers' => [
                    'Egyptians' => true,
                    'Romans' => false,
                    'Greeks' => false,
                    'Aztecs' => false,
                ],
            ],
            [
                'question' => 'Who discovered America in 1492?',
                'answers' => [
                    'Christopher Columbus' => true,
                    'Vasco da Gama' => false,
                    'Marco Polo' => false,
                    'Ferdinand Magellan' => false,
                ],
            ],
            [
                'question' => 'What was the name of the ship that sank in 1912?',
                'answers' => [
                    'Titanic' => true,
                    'Olympic' => false,
                    'Lusitania' => false,
                    'Britannic' => false,
                ],
            ],
            [
                'question' => 'Who was the leader of Nazi Germany?',
                'answers' => [
                    'Adolf Hitler' => true,
                    'Joseph Stalin' => false,
                    'Winston Churchill' => false,
                    'Benito Mussolini' => false,
                ],
            ],
            [
                'question' => 'The Great Wall of China was primarily built to protect against which group?',
                'answers' => [
                    'Mongols' => true,
                    'Romans' => false,
                    'Japanese' => false,
                    'Vikings' => false,
                ],
            ],
            [
                'question' => 'Where was Napoleon Bonaparte from?',
                'answers' => [
                    'France' => true,
                    'Italy' => false,
                    'Germany' => false,
                    'Spain' => false,
                ],
            ],
            [
                'question' => 'Which empire was ruled by Julius Caesar?',
                'answers' => [
                    'Roman Empire' => true,
                    'Ottoman Empire' => false,
                    'Persian Empire' => false,
                    'British Empire' => false,
                ],
            ],
            [
                'question' => 'What was the name of the period of cultural rebirth in Europe?',
                'answers' => [
                    'Renaissance' => true,
                    'Middle Ages' => false,
                    'Industrial Revolution' => false,
                    'Enlightenment' => false,
                ],
            ],
        ];

        foreach ($questions as $question) {
            $q = Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => $question['question'],
            ]);

            foreach ($question['answers'] as $answer => $is_correct) {
                Answer::create([
                    'question_id' => $q->id,
                    'answer_text' => $answer,
                    'is_correct' => $is_correct,
                ]);
            }

        }

        for ($i = 1; $i <= 40; $i++) {
            QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'student_name' => fake()->name(),
                'student_identifier' => fake()->randomNumber(7),
                'score' => fake()->randomElement([10, 40, 50, 70, 100]),
            ]);

        }

    }
}
