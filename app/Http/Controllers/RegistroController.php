<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;

class RegistroController extends Controller
{
    // SALVAR JSON (POST)
    public function store(Request $request)
    {
        $registro = Registro::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'dados' => json_encode($request->dados),
        ]);

        return response()->json([
            'message' => 'Salvo com sucesso',
            'data' => $registro
        ]);
    }

    // LISTAR (GET)
    public function index()
    {
        return response()->json(Registro::all());
    }
}
