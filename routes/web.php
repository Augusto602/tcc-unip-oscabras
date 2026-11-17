<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\RoboController;
use App\Http\Controllers\MonitoramentoController;
use App\Http\Controllers\InformacoesController;


/*
|--------------------------------------------------------------------------
| ROTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

// Login
Route::get('/', [AuthController::class, 'login']) ->name('login');

Route::post('/login', [AuthController::class, 'autenticar']) ->name('login.enviar');


// Cadastro de novo usuário
Route::get('/cadastro', [AuthController::class, 'cadastro']) ->name('cadastro');

Route::post('/cadastro', [AuthController::class, 'salvarCadastro']) ->name('cadastro.salvar');


/*
|--------------------------------------------------------------------------
| ROTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/

Route::middleware(['autenticado'])->group(function () {

    // Informações
    Route::get('/informacoes', [InformacoesController::class, 'index']) ->name('index.informacoes');



    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']) ->name('dashboard');


    // Carrinhos
    Route::get('/carrinhos', [CarrinhoController::class, 'index']) ->name('carrinhos.index');


    // Clientes
    Route::get('/clientes', [ClienteController::class, 'index']) ->name('clientes.index');

    Route::post('/clientes', [ClienteController::class, 'store']) ->name('clientes.store');


    // Monitoramento
    Route::get('/monitoramento', [RoboController::class, 'index']) ->name('monitoramento');


    Route::get('/monitoramento/dados', [MonitoramentoController::class, 'dados']) ->name('monitoramento.dados');

    Route::get('/monitoramento/rota', [MonitoramentoController::class, 'estadoRota'])->name('monitoramento.rota.estado');

    Route::post('/monitoramento/rota', [MonitoramentoController::class, 'iniciarRota'])->name('monitoramento.rota.iniciar');

    Route::get('/monitoramento/deteccoes',[\App\Http\Controllers\DeteccoesMonitoramentoController::class, 'index']
);

    // Robô
    Route::get('/robo', [RoboController::class, 'index']) ->name('robo.index');


    // Logout
    Route::post('/logout', [AuthController::class, 'logout']) ->name('logout');

});