<?php

namespace App\Filament\Resources\QuizResource\Widgets;

use App\Models\Quiz;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuizAttemptsWidget extends BaseWidget
{
    public ?Quiz $record = null;

    protected function getStats(): array
    {
        $highest = $this->record->attempts->sortByDesc('score')->first();
        $lowest = $this->record->attempts->sortBy('score')->first();

        $highestTitle = is_null($highest) ? 'No scores yet' : $highest->score . '%';
        $lowestTitle = is_null($lowest) ? 'No scores yet' : $lowest->score . '%';

        //return  By: ' . $highest->student_name;
        return [
            Stat::make('Total Attempts', $this->record->attempts->count())
                ->icon('heroicon-m-chart-bar-square')
                ->description('Total number of attempts for this quiz'),
            Stat::make('Highest Score', $highestTitle)
                ->icon('heroicon-m-chevron-double-up')
                ->color('success')
                ->description(is_null($highest) ? '-' : 'Scored by '.$highest->student_name),
            Stat::make('Lowest Score', $lowestTitle)
                ->icon('heroicon-m-chevron-double-down')
                ->color('warning')
                ->description(is_null($lowest) ? '-' : 'Scored by '.$lowest->student_name),
        ];
    }
}
