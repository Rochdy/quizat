<?php

namespace App\Filament\Exports;

use App\Models\QuizAttempt;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class QuizAttemptExporter extends Exporter
{
    protected static ?string $model = QuizAttempt::class;

    public static function getColumns(): array
    {
        return [
            //            ExportColumn::make('id')
            //                ->label('ID'),
            ExportColumn::make('quiz.title'),
            ExportColumn::make('student_name'),
            ExportColumn::make('student_identifier'),
            ExportColumn::make('created_at'),
            ExportColumn::make('score'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your quiz attempt export has completed and '.number_format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
