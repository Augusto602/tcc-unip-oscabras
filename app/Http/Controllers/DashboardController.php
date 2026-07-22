<?php

namespace App\Http\Controllers;

use App\Models\DadoMicroclimatico;
use App\Models\EventoDeteccao;
use App\Models\Execucao;

class DashboardController extends Controller
{
    public function index()
    {
        $resumo = Execucao::resumoMicroclima();
        $dados = DadoMicroclimatico::orderByDesc('data_hora')->take(5)->get();
        $execucoes = Execucao::orderByDesc('data_execucao')->take(5)->get();
        $eventos = EventoDeteccao::orderByDesc('data_hora')->take(5)->get();

        return view('dashboard.index', compact('resumo', 'dados', 'execucoes', 'eventos'));
    }
}
