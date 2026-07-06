<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\KitController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

// Rotas Públicas
Route::post('/login', [AuthController::class, 'login']);
Route::post('/registo', [AuthController::class, 'register']);

// Rotas Protegidas (Só acessíveis com Token após o Login)
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::apiResource('categorias', CategoriaController::class);
    Route::apiResource('kits', KitController::class);
    Route::apiResource('items', ItemController::class);
    
});