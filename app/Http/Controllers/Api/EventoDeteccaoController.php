<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\StoreEventoDeteccaoRequest;
use App\Models\EventoDeteccao;
use App\Models\Execucao;
use Illuminate\Http\Request;

class EventoDeteccaoController extends Controller
{
    public function index(Request $request)
    {
        $query = EventoDeteccao::query();

        if ($request->filled('id_execucao')) {
            $query->where('id_execucao', (int) $request->id_execucao);
        }

        return $this->sucesso(
            $query->orderByDesc('data_hora')->get(),
            'Eventos de detecção listados com sucesso.'
        );
    }

    public function store(StoreEventoDeteccaoRequest $request)
    {
        if (!Execucao::where('id_execucao', $request->id_execucao)->exists()) {
            return $this->erro('Execução informada não existe.', 404);
        }

        $evento = EventoDeteccao::create([
            'id_execucao' => (int) $request->id_execucao,
            'data_hora' => $request->input('data_hora', now()),
            'tipo_evento' => $request->tipo_evento,
            'tipo_obstaculo' => $request->tipo_obstaculo,
            'score_confianca' => $request->filled('score_confianca')
                ? (float) $request->score_confianca
                : null,
        ]);

        return $this->sucesso($evento, 'Evento de detecção salvo com sucesso.', 201);
    }
}
