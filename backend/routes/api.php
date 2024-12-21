<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovimentacaoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['cors'])->group(function(){
    Route::post('/upload', [MovimentacaoController::class, 'upload']);
});

Route::get('/dashboard', [MovimentacaoController::class, 'dashboard']);

