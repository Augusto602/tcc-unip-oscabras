<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;

class CarrinhoController extends Controller
{
    public function index()
    {
        $carrinhos = Veiculo::with('execucoes')->orderBy('id_veiculo')->get();

        return view('carrinhos.index', compact('carrinhos'));
    }
}
