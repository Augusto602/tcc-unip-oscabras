<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\StoreDadoMicroclimaticoRequest;
use App\Models\DadoMicroclimatico;
use App\Models\Execucao;
use Illuminate\Http\Request;

class DadoMicroclimaticoController extends Controller
{
    public function index(Request $request)
    {
        $query = DadoMicroclimatico::query();

        if ($request->filled('id_execucao')) {
            $query->where('id_execucao', (int) $request->id_execucao);
        }

        return $this->sucesso(
            $query->orderByDesc('data_hora')->get(),
            'Dados microclimáticos listados com sucesso.'
        );
    }

    public function store(StoreDadoMicroclimaticoRequest $request)
    {
        if (!Execucao::where('id_execucao', $request->id_execucao)->exists()) {
            return $this->erro('Execução informada não existe.', 404);
        }

        $registro = DadoMicroclimatico::create([
            'id_execucao' => (int) $request->id_execucao,
            'data_hora' => $request->input('data_hora', now()),
            'temperatura' => (float) $request->temperatura,
            'umidade' => (float) $request->umidade,
            'latitude' => (float) $request->latitude,
            'longitude' => (float) $request->longitude,
        ]);

        return $this->sucesso($registro, 'Temperatura e umidade salvas com sucesso.', 201);
    }

    public function storeLote(Request $request)
    {
        $request->validate([
            'registros' => 'required|array|min:1',
            'registros.*.id_execucao' => 'required|integer|exists:execucoes,id_execucao',
            'registros.*.temperatura' => 'required|numeric|between:-40,80',
            'registros.*.umidade' => 'required|numeric|between:0,100',
            'registros.*.latitude' => 'required|numeric',
            'registros.*.longitude' => 'required|numeric',
            'registros.*.data_hora' => 'nullable|date',
        ]);

        $inseridos = [];

        foreach ($request->registros as $item) {
            $inseridos[] = DadoMicroclimatico::create([
                'id_execucao' => (int) $item['id_execucao'],
                'data_hora' => $item['data_hora'] ?? now(),
                'temperatura' => (float) $item['temperatura'],
                'umidade' => (float) $item['umidade'],
                'latitude' => (float) $item['latitude'],
                'longitude' => (float) $item['longitude'],
            ]);
        }

        return $this->sucesso([
            'total' => count($inseridos),
            'registros' => $inseridos,
        ], 'Lote de temperatura/umidade salvo com sucesso.', 201);
    }
}
