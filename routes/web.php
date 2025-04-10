<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
})->name('home');
//
//Route::view('dashboard', 'dashboard')
//    ->middleware(['auth', 'verified'])
//    ->name('dashboard');
//
//Route::middleware(['auth'])->group(function () {
//    Route::redirect('settings', 'settings/profile');
//
//    Route::get('settings/profile', Profile::class)->name('settings.profile');
//    Route::get('settings/password', Password::class)->name('settings.password');
//    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
//});
//Route::get('/quiz/{quizId}', [\App\Filament\Pages\AnswerQuiz::class, 'mount']);

Route::view('/thanks', 'thanks');
require __DIR__.'/auth.php';
