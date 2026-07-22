<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\SyncTelemetriaRequest;
use App\Models\DadoMicroclimatico;
use App\Models\EventoDeteccao;
use App\Models\Execucao;
use App\Models\LogNavegacao;
use Illuminate\Support\Facades\DB;

class SyncController extends Controller
{
    /**
     * Sincronização no formato do TCC (seção 5.4).
     */
    public function telemetria(SyncTelemetriaRequest $request)
    {
        $idExecucao = (int) $request->id_execucao;

        if (!Execucao::where('id_execucao', $idExecucao)->exists()) {
            return $this->erro('Execução informada não existe.', 404);
        }

        $dataHora = $request->input('timestamp', now()->format('Y-m-d H:i:s'));
        $dataHora = str_replace('T', ' ', $dataHora);

        $resultado = DB::transaction(function () use ($request, $idExecucao, $dataHora) {
            $microclima = DadoMicroclimatico::create([
                'id_execucao' => $idExecucao,
                'data_hora' => $dataHora,
                'temperatura' => (float) $request->temperatura,
                'umidade' => (float) $request->umidade,
                'latitude' => (float) $request->latitude,
                'longitude' => (float) $request->longitude,
            ]);

            $log = null;
            if ($request->filled('distancia_percorrida') || $request->filled('velocidade_media')) {
                $log = LogNavegacao::create([
                    'id_execucao' => $idExecucao,
                    'data_hora' => $dataHora,
                    'distancia_percorrida' => (float) $request->input('distancia_percorrida', 0),
                    'tempo_decorrido' => $request->input('tempo_decorrido', '00:00:00'),
                    'velocidade_media' => (float) $request->input('velocidade_media', 0),
                    'latitude' => (float) $request->latitude,
                    'longitude' => (float) $request->longitude,
                ]);
            }

            $evento = null;
            if ($request->boolean('obstaculo_detectado')) {
                $tipoEvento = 'obstaculo';
                $acao = strtolower((string) $request->input('acao_executada', ''));

                if (str_contains($acao, 'parada') || str_contains($acao, 'emerg')) {
                    $tipoEvento = 'parada_emergencial';
                } elseif (str_contains($acao, 'desvio')) {
                    $tipoEvento = 'desvio';
                }

                $evento = EventoDeteccao::create([
                    'id_execucao' => $idExecucao,
                    'data_hora' => $dataHora,
                    'tipo_evento' => $tipoEvento,
                    'tipo_obstaculo' => $request->input('tipo_obstaculo', $request->input('acao_executada')),
                    'score_confianca' => $request->filled('score_confianca')
                        ? (float) $request->score_confianca
                        : null,
                ]);
            }

            return compact('microclima', 'log', 'evento');
        });

        return $this->sucesso([
            'microclima' => $resultado['microclima'],
            'log_navegacao' => $resultado['log'],
            'evento_deteccao' => $resultado['evento'],
        ], 'Telemetria sincronizada com sucesso.', 201);
    }
}
