<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\StoreTelemetriaRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TelemetriaController extends Controller
{
    public function store(StoreTelemetriaRequest $request)
    {
        $dados = $request->validated();

        return DB::transaction(function () use ($dados, $request) {
            // Mesmo critério usado por MonitoramentoController::estadoRota().
            $execucao = DB::table('execucoes')
                ->where('status_execucao', 'em_andamento')
                ->orderByDesc('id_execucao')
                ->lockForUpdate()
                ->first();

            if (!$execucao) {
                return $this->erro('Não existe uma execução em andamento para receber a telemetria.', 409);
            }

            $dataHora = $this->dataHoraBanco($dados['data_hora']);
            $idDado = DB::table('dados_microclimaticos')->insertGetId([
                'id_execucao' => $execucao->id_execucao,
                'data_hora' => $dataHora,
                'latitude_X' => $dados['coordenadas']['x'],
                'longitude_Y' => $dados['coordenadas']['y'],
                'temperatura' => $dados['temperatura'],
                'umidade' => $dados['umidade'],
                'telemetria_original' => json_encode($request->json()->all(), JSON_THROW_ON_ERROR),
            ], 'id_dado');

            foreach ($dados['objetos_detectados'] as $objeto) {
                DB::table('eventos_deteccao')->insert([
                    'id_execucao' => $execucao->id_execucao,
                    'data_hora' => $this->dataHoraBanco($objeto['data_hora_observacao']),
                    'tipo_evento' => 'alerta',
                    'tipo_obstaculo' => $objeto['classe'],
                    'score_confianca' => $objeto['confianca'],
                ]);
            }

            return $this->sucesso([
                'id_execucao' => (int) $execucao->id_execucao,
                'id_dado' => (int) $idDado,
                'data_hora' => $dataHora,
                'eventos_recebidos' => count($dados['objetos_detectados']),
            ], 'Telemetria recebida com sucesso.', 201);
        });
    }

    private function dataHoraBanco(string $valor): string
    {
        return Carbon::parse($valor)
            ->setTimezone(config('app.timezone'))
            ->format('Y-m-d H:i:s');
    }
}
