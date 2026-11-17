<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DeteccoesMonitoramentoController extends Controller
{
    /**
     * Retorna detecções que possuem coordenadas de observação na telemetria.
     * Não identifica a posição física do obstáculo, somente a posição estimada
     * do robô no instante da observação.
     */
    public function index(Request $request)
    {
        $request->validate([
            'execucao' => 'required|integer|min:1',
        ]);

        $idExecucao = (int) $request->query('execucao');

        // Não aceitar um ID inexistente (e autorizar por usuário/política quando necessário).
        $existe = DB::table('execucoes')->where('id_execucao', $idExecucao)->exists();
        if (!$existe) {
            return response()->json(['message' => 'Execução não encontrada'], 404);
        }

        $medicoes = DB::table('dados_microclimaticos')
            ->where('id_execucao', $idExecucao)
            ->whereNotNull('telemetria_original')
            ->orderBy('data_hora')
            ->orderBy('id_dado')
            ->get(['id_dado', 'data_hora', 'latitude_X', 'longitude_Y', 'telemetria_original']);

        $objetos = [];
        foreach ($medicoes as $medicao) {
            $json = json_decode($medicao->telemetria_original, true);
            if (!is_array($json) || !isset($json['objetos_detectados']) || !is_array($json['objetos_detectados'])) {
                continue;
            }

            foreach ($json['objetos_detectados'] as $indice => $objeto) {
                if (!is_array($objeto)) {
                    continue;
                }
                $posicao = isset($objeto['coordenadas_observacao']) && is_array($objeto['coordenadas_observacao'])
                    ? $objeto['coordenadas_observacao'] : [];
                $x = isset($posicao['x']) && is_numeric($posicao['x']) ? (float) $posicao['x'] : null;
                $y = isset($posicao['y']) && is_numeric($posicao['y']) ? (float) $posicao['y'] : null;
                $confianca = isset($objeto['confianca']) && is_numeric($objeto['confianca'])
                    ? (float) $objeto['confianca'] : null;

                $objetos[] = [
                    'id' => $medicao->id_dado . '-' . $indice,
                    'tipo' => isset($objeto['classe']) ? (string) $objeto['classe'] : 'Objeto',
                    'confianca' => $confianca,
                    'x' => $x,
                    'y' => $y,
                    'data_hora' => isset($objeto['data_hora_observacao'])
                        ? (string) $objeto['data_hora_observacao'] : (string) $medicao->data_hora,
                    'coordenadas_estimadas' => !empty($json['coordenadas_estimadas']),
                    'origem' => 'telemetria_original',
                ];
            }
        }

        return response()->json($objetos);
    }
}
