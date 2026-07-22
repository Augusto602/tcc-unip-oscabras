<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\StoreLogNavegacaoRequest;
use App\Models\Execucao;
use App\Models\LogNavegacao;
use Illuminate\Http\Request;

class LogNavegacaoController extends Controller
{
    public function index(Request $request)
    {
        $query = LogNavegacao::query();

        if ($request->filled('id_execucao')) {
            $query->where('id_execucao', (int) $request->id_execucao);
        }

        return $this->sucesso(
            $query->orderByDesc('data_hora')->get(),
            'Rotas/logs de navegação listados com sucesso.'
        );
    }

    public function store(StoreLogNavegacaoRequest $request)
    {
        if (!Execucao::where('id_execucao', $request->id_execucao)->exists()) {
            return $this->erro('Execução informada não existe.', 404);
        }

        $log = LogNavegacao::create([
            'id_execucao' => (int) $request->id_execucao,
            'data_hora' => $request->input('data_hora', now()),
            'distancia_percorrida' => (float) $request->distancia_percorrida,
            'tempo_decorrido' => $request->tempo_decorrido,
            'velocidade_media' => (float) $request->velocidade_media,
            'latitude' => $request->filled('latitude') ? (float) $request->latitude : null,
            'longitude' => $request->filled('longitude') ? (float) $request->longitude : null,
        ]);

        return $this->sucesso($log, 'Rota/log de navegação salvo com sucesso.', 201);
    }

    public function storeLote(Request $request)
    {
        $request->validate([
            'registros' => 'required|array|min:1',
            'registros.*.id_execucao' => 'required|integer|exists:execucoes,id_execucao',
            'registros.*.distancia_percorrida' => 'required|numeric|min:0',
            'registros.*.tempo_decorrido' => 'required|string',
            'registros.*.velocidade_media' => 'required|numeric|min:0',
            'registros.*.data_hora' => 'nullable|date',
            'registros.*.latitude' => 'nullable|numeric',
            'registros.*.longitude' => 'nullable|numeric',
        ]);

        $inseridos = [];

        foreach ($request->registros as $item) {
            $inseridos[] = LogNavegacao::create([
                'id_execucao' => (int) $item['id_execucao'],
                'data_hora' => $item['data_hora'] ?? now(),
                'distancia_percorrida' => (float) $item['distancia_percorrida'],
                'tempo_decorrido' => $item['tempo_decorrido'],
                'velocidade_media' => (float) $item['velocidade_media'],
                'latitude' => isset($item['latitude']) ? (float) $item['latitude'] : null,
                'longitude' => isset($item['longitude']) ? (float) $item['longitude'] : null,
            ]);
        }

        return $this->sucesso([
            'total' => count($inseridos),
            'registros' => $inseridos,
        ], 'Lote de rotas/logs salvo com sucesso.', 201);
    }
}
