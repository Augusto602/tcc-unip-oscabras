<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoramentoController extends Controller
{
    public function dados(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | ROTA RECEBIDA PELO HISTÓRICO
        |--------------------------------------------------------------------------
        |
        | Exemplo:
        |
        | /monitoramento?rota=mov-20260917
        |
        */

        $codigoRota = $request->query('rota');


        /*
        |--------------------------------------------------------------------------
        | SE NÃO RECEBEU ROTA, BUSCA A ROTA MAIS RECENTE
        |--------------------------------------------------------------------------
        */

        if (empty($codigoRota)) {

            $ultimaMedicao = DB::connection('mysql_monitoramento')
                ->table('medicoes')
                ->orderBy('recebida_em', 'desc')
                ->first();


            if (!$ultimaMedicao) {

                return response()->json([]);

            }


            /*
            |--------------------------------------------------------------------------
            | REMOVE O NÚMERO FINAL
            |--------------------------------------------------------------------------
            |
            | mov-20260918-005
            |
            | vira:
            |
            | mov-20260918
            |
            */

            $codigoRota = preg_replace(
                '/-\d+$/',
                '',
                $ultimaMedicao->idempotency_key
            );

        }


        /*
        |--------------------------------------------------------------------------
        | BUSCA SOMENTE AS MEDIÇÕES DA ROTA
        |--------------------------------------------------------------------------
        */

        $medicoes = DB::connection('mysql_monitoramento')
            ->table('medicoes')
            ->where(
                'idempotency_key',
                'like',
                $codigoRota . '-%'
            )
            ->orderBy(
                'recebida_em',
                'asc'
            )
            ->get();


        return response()->json($medicoes);
    }
}