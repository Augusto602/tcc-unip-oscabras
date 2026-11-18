<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $execucoes = DB::table('execucoes')
            ->leftJoin(
                'veiculos',
                'execucoes.id_veiculo',
                '=',
                'veiculos.id_veiculo'
            )
            ->leftJoin(
                'dados_microclimaticos',
                'execucoes.id_execucao',
                '=',
                'dados_microclimaticos.id_execucao'
            )
            ->select(
                'execucoes.id_execucao',
                'execucoes.nome_execucao',
                'execucoes.data_execucao',
                'execucoes.local_execucao',
                'execucoes.status_execucao',
                'veiculos.nome_veiculo',
                DB::raw('MIN(dados_microclimaticos.data_hora) as data_inicio'),
                DB::raw('MAX(dados_microclimaticos.data_hora) as data_fim'),
                DB::raw('COUNT(dados_microclimaticos.id_dado) as quantidade_medicoes')
            )
            ->groupBy(
                'execucoes.id_execucao',
                'execucoes.nome_execucao',
                'execucoes.data_execucao',
                'execucoes.local_execucao',
                'execucoes.status_execucao',
                'veiculos.nome_veiculo'
            )
            ->orderByDesc('execucoes.data_execucao')
            ->orderByDesc('execucoes.id_execucao')
            ->get();

        $rotas = $execucoes
            ->map(function ($execucao) {

                // DATA DE INICIO
                if (!empty($execucao->data_inicio)) {
                    $dataInicio = Carbon::parse($execucao->data_inicio);
                } else {
                    $dataInicio = Carbon::parse($execucao->data_execucao);
                }

                // DATA DE FIM
                if (!empty($execucao->data_fim)) {
                    $dataFim = Carbon::parse($execucao->data_fim);
                } else {
                    $dataFim = $dataInicio->copy();
                }

                // DURACAO
                $duracaoMinutos = $dataInicio->diffInMinutes($dataFim);

                $horas = intdiv($duracaoMinutos, 60);
                $minutos = $duracaoMinutos % 60;

                if ($horas > 0) {
                    $duracao = sprintf(
                        '%02dh %02dmin',
                        $horas,
                        $minutos
                    );
                } else {
                    $duracao = sprintf('%02d min', $minutos);
                }

                // STATUS REAL DA EXECUCAO
                $statusBanco = trim((string) $execucao->status_execucao);

                $statusMapeados = [
                    'em_andamento' => 'Em atividade',
                    'concluida' => 'Finalizada',
                    'abortada' => 'Abortada',
                    'falha' => 'Falha',
                ];

                $status = $statusMapeados[$statusBanco]
                    ?? 'Desconhecido';

                // MONTA ROTA
                return (object) [
                    'codigo' => $execucao->id_execucao,

                    'nome' => $execucao->nome_execucao,

                    'data_inicio' => $dataInicio->format('Y-m-d H:i:s'),

                    'data_fim' => $dataFim->format('Y-m-d H:i:s'),

                    'veiculo' => !empty($execucao->nome_veiculo)
                        ? $execucao->nome_veiculo
                        : 'Não informado',

                    'duracao' => $duracao,

                    'status' => $status,

                    'status_execucao' => $statusBanco,

                    'quantidade_medicoes' => $execucao->quantidade_medicoes,

                    'local_execucao' => $execucao->local_execucao,
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
