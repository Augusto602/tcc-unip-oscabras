<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\RoboController;
use App\Http\Controllers\MonitoramentoController;


Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.enviar');

Route::middleware(['autenticado'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/carrinhos', [CarrinhoController::class, 'index'])->name('carrinhos.index');
    Route::get('/monitoramento/dados', [MonitoramentoController::class, 'dados']);

    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/monitoramento', [RoboController::class, 'index'])->name('monitoramento');

    Route::get('/robo', [RoboController::class, 'index'])->name('robo.index');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
