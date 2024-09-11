<?php

use App\Modules\lens\controllers\GoogleLensController;
use Illuminate\Support\Facades\Route;


Route::post('/google-lens/get-results', [GoogleLensController::class, '__invoke']);
