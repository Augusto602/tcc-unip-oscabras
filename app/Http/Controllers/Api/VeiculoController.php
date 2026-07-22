<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\StoreVeiculoRequest;
use App\Models\Usuario;
use App\Models\Veiculo;

class VeiculoController extends Controller
{
    public function index()
    {
        $veiculos = Veiculo::with('execucoes')->orderBy('id_veiculo')->get();

        return $this->sucesso($veiculos, 'Veículos listados com sucesso.');
    }

    public function show($id)
    {
        $veiculo = Veiculo::with('execucoes')->find($id);

        if (!$veiculo) {
            return $this->erro('Veículo não encontrado.', 404);
        }

        return $this->sucesso($veiculo);
    }

    public function store(StoreVeiculoRequest $request)
    {
        /** @var Usuario $usuario */
        $usuario = $request->attributes->get('usuario_api');

        $veiculo = Veiculo::create([
            'id_usuario' => $request->input('id_usuario', $usuario->id_usuario),
            'nome_veiculo' => $request->nome_veiculo,
            'tipo_modelo' => $request->tipo_modelo,
            'fabricante' => $request->fabricante,
            'especificacoes_hardware' => $request->especificacoes_hardware,
        ]);

        return $this->sucesso($veiculo, 'Veículo cadastrado com sucesso.', 201);
    }
}
