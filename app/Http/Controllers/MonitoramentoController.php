<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoramentoController extends Controller
{
    public function dados(Request $request)
    {
        $idExecucao = $request->query('rota');

        /*
        |--------------------------------------------------------------------------
        | SEM ROTA INFORMADA
        |--------------------------------------------------------------------------
        |
        | Busca a execução mais recente.
        |
        */

        if (empty($idExecucao)) {

            $ultimaExecucao = DB::table('execucoes')
                ->orderByDesc('data_execucao')
                ->orderByDesc('id_execucao')
                ->first();

            if (!$ultimaExecucao) {

                return response()->json([]);

            }

            $idExecucao =
                $ultimaExecucao->id_execucao;
        }


        /*
        |--------------------------------------------------------------------------
        | DADOS DA EXECUÇÃO
        |--------------------------------------------------------------------------
        |
        | Mantemos os aliases x, y e recebida_em
        | para não precisar refazer todo o JavaScript.
        |
        */

        $medicoes = DB::table('dados_microclimaticos')
            ->where(
                'id_execucao',
                $idExecucao
            )
            ->select(
                'id_dado',
                'id_execucao',
                DB::raw('latitude_X as x'),
                DB::raw('longitude_Y as y'),
                'temperatura',
                'umidade',
                DB::raw('data_hora as recebida_em')
            )
            ->orderBy(
                'data_hora',
                'asc'
            )
            ->get();


        return response()->json(
            $medicoes
        );
    }
}