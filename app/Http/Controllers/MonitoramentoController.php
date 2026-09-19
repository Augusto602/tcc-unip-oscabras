<?php

namespace App\Http\Controllers;

use App\Models\Medicao;

class MonitoramentoController extends Controller
{
    public function dados()
    {
        $medicoes = Medicao::orderBy('recebida_em', 'asc')->get();

        return response()->json($medicoes);
    }
}