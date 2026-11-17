<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VeiculoController;
use App\Http\Controllers\Api\ExecucaoController;
use App\Http\Controllers\Api\DadoMicroclimaticoController;
use App\Http\Controllers\Api\LogNavegacaoController;
use App\Http\Controllers\Api\EventoDeteccaoController;
use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\Api\TelemetriaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/telemetria', [TelemetriaController::class, 'store']);

Route::get('/execucoes/status', [ExecucaoController::class, 'status']);

Route::middleware('api.autenticado')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Veículos
    Route::get('/veiculos', [VeiculoController::class, 'index']);
    Route::get('/veiculos/{id}', [VeiculoController::class, 'show']);
    Route::post('/veiculos', [VeiculoController::class, 'store']);

    // Execuções
    Route::get('/execucoes', [ExecucaoController::class, 'index']);
    Route::get('/execucoes/{id}', [ExecucaoController::class, 'show']);
    Route::post('/execucoes', [ExecucaoController::class, 'store']);
    Route::put('/execucoes/{id}', [ExecucaoController::class, 'update']);
    Route::patch('/execucoes/{id}', [ExecucaoController::class, 'update']);
    Route::get('/execucoes/{id}/mapa-microclima', [ExecucaoController::class, 'mapaMicroclima']);

    // Temperatura e umidade
    Route::get('/dados-microclimaticos', [DadoMicroclimaticoController::class, 'index']);
    Route::post('/dados-microclimaticos', [DadoMicroclimaticoController::class, 'store']);
    Route::post('/dados-microclimaticos/lote', [DadoMicroclimaticoController::class, 'storeLote']);

    // Rotas / logs de navegação
    Route::get('/logs-navegacao', [LogNavegacaoController::class, 'index']);
    Route::post('/logs-navegacao', [LogNavegacaoController::class, 'store']);
    Route::post('/logs-navegacao/lote', [LogNavegacaoController::class, 'storeLote']);

    // Eventos de detecção (YOLO / obstáculos)
    Route::get('/eventos-deteccao', [EventoDeteccaoController::class, 'index']);
    Route::post('/eventos-deteccao', [EventoDeteccaoController::class, 'store']);

    // Sync Android
    Route::post('/sync/telemetria', [SyncController::class, 'telemetria']);
});
