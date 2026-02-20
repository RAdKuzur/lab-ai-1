<?php

use App\Http\Controllers\AIController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return redirect()->route('ai.index');
});


Route::get('/ai', [AiController::class, 'index'])->name('ai.index');
Route::post('/chat', [AiController::class, 'chat'])->name('ai.chat');
