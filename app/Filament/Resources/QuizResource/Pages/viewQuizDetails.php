<?php

namespace App\Filament\Resources\QuizResource\Pages;

use App\Filament\Resources\QuizResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class viewQuizDetails extends ViewRecord
{
    protected static string $resource = QuizResource::class;
    protected static ?string $title = 'Quiz Details';

    protected function getHeaderWidgets(): array
    {
        return [
            QuizResource\Widgets\QuizAttemptsWidget::class,
            QuizResource\Widgets\QuizAttemptsChartWidget::class,

        ];
    }
}
