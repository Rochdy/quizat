<?php

namespace App\Filament\Resources\QuizResource\Pages;

use App\Filament\Resources\QuizResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQuiz extends CreateRecord
{
    protected static string $resource = QuizResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = auth()->id();

        return $data;
    }
}
