<?php

use App\Modules\openAi\controllers\OpenAIController;
use Illuminate\Support\Facades\Route;


Route::post('/get-gpt-advice-by-image', [OpenAiController::class, 'gptAdviceFromImage']);
Route::post('/get-gpt-advice-by-text', [OpenAiController::class, 'gptAdviceFromText']);
Route::post('/get-gpt-random-pick', [OpenAiController::class, 'gptRandomPick']);
