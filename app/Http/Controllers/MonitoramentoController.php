<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MonitoramentoController extends Controller
{
    public function dados(Request $request)
    {
        $dados = $request->validate([
            'rota' => 'nullable|integer|min:1',
        ]);
        $idExecucao = $dados['rota'] ?? null;

        if (!$idExecucao) {
            $idExecucao = DB::table('execucoes')
                ->orderByDesc('id_execucao')
                ->value('id_execucao');
        }

        if (!$idExecucao) {
            return response()->json([]);
        }

        $medicoes = DB::table('dados_microclimaticos')
            ->where('id_execucao', $idExecucao)
            ->select(
                'id_dado',
                'id_execucao',
                DB::raw('latitude_X as x'),
                DB::raw('longitude_Y as y'),
                'temperatura',
                'umidade',
                DB::raw('data_hora as recebida_em')
            )
            ->orderBy('data_hora', 'asc')
            ->orderBy('id_dado', 'asc')
            ->get();

        // Uma execução recém-iniciada pode não possuir medições.
        return response()->json($medicoes);
    }

    public function estadoRota()
    {
        $rotaAtiva = DB::table('execucoes as e')
            ->join('veiculos as v', 'v.id_veiculo', '=', 'e.id_veiculo')
            ->where('e.status_execucao', 'em_andamento')
            ->orderByDesc('e.id_execucao')
            ->select(
                'e.id_execucao',
                'e.id_execucao as codigo',
                'e.id_veiculo as veiculo_id',
                'v.nome_veiculo as veiculo_nome',
                'e.status_execucao'
            )
            ->first();

        $veiculosDisponiveis = DB::table('veiculos as v')
            ->whereNotIn('v.id_veiculo', function ($query) {
                $query->select('id_veiculo')
                    ->from('execucoes')
                    ->where('status_execucao', 'em_andamento');
            })
            ->select('v.id_veiculo as id', 'v.nome_veiculo as nome')
            ->orderBy('v.nome_veiculo')
            ->get();

        return response()->json([
            'rota_ativa' => $rotaAtiva,
            'veiculos_disponiveis' => $veiculosDisponiveis,
        ]);
    }

    public function iniciarRota(Request $request)
    {
        $dados = $request->validate([
            'veiculo_id' => 'required|integer|min:1',
            'local_execucao' => 'nullable|string|max:255',
        ]);

        return DB::transaction(function () use ($dados) {
            $rotaAtiva = DB::table('execucoes')
                ->where('status_execucao', 'em_andamento')
                ->lockForUpdate()
                ->first();

            if ($rotaAtiva) {
                return response()->json([
                    'message' => 'Já existe uma rota em andamento.',
                ], 409);
            }

            $veiculo = DB::table('veiculos')
                ->where('id_veiculo', $dados['veiculo_id'])
                ->first();

            if (!$veiculo) {
                return response()->json(['message' => 'Veículo não encontrado.'], 404);
            }

            $agora = now();
            $idExecucao = DB::table('execucoes')->insertGetId([
                'id_usuario' => $veiculo->id_usuario,
                'id_veiculo' => $veiculo->id_veiculo,
                'data_execucao' => $agora->toDateString(),
                'local_execucao' => $dados['local_execucao'] ?? 'Área de monitoramento',
                'status_execucao' => 'em_andamento',
                'nome_execucao' => 'Rota V' . $veiculo->id_veiculo . '-'
                    . $agora->format('YmdHis') . '-' . Str::upper(Str::random(5)),
            ]);

            return response()->json([
                'rota' => [
                    'id_execucao' => $idExecucao,
                    'codigo' => (string) $idExecucao,
                    'veiculo_id' => $veiculo->id_veiculo,
                    'veiculo_nome' => $veiculo->nome_veiculo,
                    'status_execucao' => 'em_andamento',
                ],
            ], 201);
        });
    }

    public function pararRota(Request $request)
    {
        $dados = $request->validate([
            'id_execucao' => 'required|integer|min:1',
        ]);
        $idExecucao = (int) $dados['id_execucao'];

        // Atualiza uma única execução ativa. Nunca aborta outras rotas.
        $atualizadas = DB::table('execucoes')
            ->where('id_execucao', $idExecucao)
            ->where('status_execucao', 'em_andamento')
            ->update(['status_execucao' => 'abortada']);

        if ($atualizadas === 0) {
            return response()->json([
                'message' => 'Execução não encontrada ou não está em andamento.',
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => 'Execução abortada no banco de dados.',
            'id_execucao' => $idExecucao,
            'status_execucao' => 'abortada',
        ]);
    }
}
