<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $medicoes = DB::connection('mysql_monitoramento')
            ->table('medicoes')
            ->orderBy('recebida_em', 'asc')
            ->get();

        $rotas = $medicoes
            ->groupBy(function ($medicao) {

                return preg_replace(
                    '/-\d+$/',
                    '',
                    $medicao->idempotency_key
                );

            })
            ->map(function ($grupo, $codigoRota) {

                $primeiraMedicao = $grupo->first();
                $ultimaMedicao = $grupo->last();

                $dataInicio = Carbon::parse(
                    $primeiraMedicao->recebida_em
                );

                $dataFim = Carbon::parse(
                    $ultimaMedicao->recebida_em
                );

                $duracaoMinutos = $dataInicio->diffInMinutes(
                    $dataFim
                );

                $horas = intdiv(
                    $duracaoMinutos,
                    60
                );

                $minutos = $duracaoMinutos % 60;

                if ($horas > 0) {

                    $duracao = sprintf(
                        '%02dh %02dmin',
                        $horas,
                        $minutos
                    );

                } else {

                    $duracao = sprintf(
                        '%02d min',
                        $minutos
                    );

                }

                return (object) [

                    'codigo' => $codigoRota,

                    'nome' => 'Rota ' .
                        str_replace(
                            'mov-',
                            '',
                            $codigoRota
                        ),

                    'data_inicio' =>
                        $primeiraMedicao->recebida_em,

                    'data_fim' =>
                        $ultimaMedicao->recebida_em,

                    'veiculo' =>
                        'Robô 01',

                    'duracao' =>
                        $duracao,

                    'status' =>
                        'Finalizada',

                    'quantidade_medicoes' =>
                        $grupo->count(),

                ];

            })
            ->sortByDesc('data_inicio')
            ->values();

        return view(
            'dashboard.index',
            compact('rotas')
        );
    }
}