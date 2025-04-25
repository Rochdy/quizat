<?php

namespace App\Filament\Resources\QuizResource\Widgets;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Filament\Widgets\ChartWidget;

class QuizAttemptsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Number of students with their scores';

    public ?Quiz $record = null;

    public int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = QuizAttempt::where('quiz_id', $this->record->id)
            ->selectRaw('score, COUNT(*) as count')
            ->groupBy('score')
            ->orderBy('score')
            ->pluck('count', 'score');

        return [
            'datasets' => [
                [
                    'label' => 'Number of Students',
                    'data' => $data->values(),
                ],
            ],
            'labels' => $data->keys()->map(fn ($score) => $score.'%')->values(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
