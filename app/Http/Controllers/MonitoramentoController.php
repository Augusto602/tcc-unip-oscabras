<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class MonitoramentoController extends Controller
{
    public function dados()
    {
        $medicoes = DB::connection('mysql_monitoramento')
            ->table('medicoes')
            ->orderBy('recebida_em', 'asc')
            ->get();

        return response()->json($medicoes);
    }
}
