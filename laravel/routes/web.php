<?php

use App\Http\Controllers\AIController;
use Illuminate\Support\Facades\Route;

Route::get('/ai', [AiController::class, 'index'])->name('ai.index');
Route::post('/chat', [AiController::class, 'chat'])->name('ai.chat');
