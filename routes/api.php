<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\todoController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/todos', [todoController::class, 'store']);
Route::get('/todos/report', [todoController::class, 'generateReport']);
Route::get('/chart', [todoController::class, 'getChartData']);
