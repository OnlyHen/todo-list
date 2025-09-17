<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TodoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', function () {
    return response()->json(['message' => 'API is running']);
});

Route::post('/todos', [TodoController::class, 'store']);
Route::get('/todos/report', [TodoController::class, 'generateReport']);
Route::get('/chart', [TodoController::class, 'getChartData']);
