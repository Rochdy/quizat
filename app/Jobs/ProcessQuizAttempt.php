<?php

namespace App\Jobs;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessQuizAttempt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public QuizAttempt $quizAttempt,
    ) {}

    public function handle(): void
    {
        $total = count($this->quizAttempt->quiz->questions);
        $score = 0;

        foreach ($this->quizAttempt->quiz->questions as $question) {
            $correctAnswers = $question->answers->where('is_correct', true)->first();
            $userAnswers = $this->quizAttempt->attemptAnswers->where('question_id', $question->id)->first();

            if ($correctAnswers->id === $userAnswers->answer_id) {
                $score++;
            }
        }

        $this->quizAttempt->update([
            'score' => ($score / $total) * 100,
        ]);
        //
        //        foreach ($this->quizAttempt->quiz->questions as $question) {
        //            $correctAnswers = $question->answers->where('is_correct', true)->first();
        //            $userAnswers = $this->quizAttempt->attemptAnswers->where('question_id', $question->id)->first();
        //
        //            if ($correctAnswers->id === $userAnswers->id) {
        //                $score++;
        //            }
        //        }
        //
        //        // Save quiz attempt and answers
        //        $attempt = QuizAttempt::create([
        //            'quiz_id' => $this->quiz->id,
        //            'student_name' => $this->data['student_name'],
        //            'student_identifier' => $this->data['student_identifier'],
        //            'score' => $score,
        //        ]);
        //
        //        foreach ($this->data['answers'] as $questionId => $answerIds) {
        //            foreach ((array) $answerIds as $answerId) {
        //                $attempt->answers()->create([
        //                    'question_id' => $questionId,
        //                    'answer_id' => $answerId,
        //                ]);
        //            }
        //        }
    }
}
