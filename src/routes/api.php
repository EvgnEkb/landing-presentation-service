<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactController;

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:' . env('RATE_LIMIT_ATTEMPTS', 5) . ',' . env('RATE_LIMIT_DECAY', 1));
