<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\StoreExecucaoRequest;
use App\Http\Requests\Api\UpdateExecucaoRequest;
use App\Models\Execucao;
use App\Models\Usuario;
use App\Models\Veiculo;
use Illuminate\Http\Request;

class ExecucaoController extends Controller
{
    public function index(Request $request)
    {
        $query = Execucao::query();

        if ($request->filled('id_veiculo')) {
            $query->where('id_veiculo', (int) $request->id_veiculo);
        }

        if ($request->filled('status_execucao')) {
            $query->where('status_execucao', $request->status_execucao);
        }

        $execucoes = $query->orderByDesc('data_execucao')->get();

        return $this->sucesso($execucoes, 'Execuções listadas com sucesso.');
    }

    public function status()
    {
        $execucao = Execucao::query()
            ->orderByDesc('data_execucao')
            ->orderByDesc('id_execucao')
            ->first(['status_execucao']);

        if (!$execucao) {
            return $this->erro('Execução não encontrada.', 404);
        }

        return response()->json([
            'status_execucao' => $execucao->status_execucao,
        ]);
    }

    public function show($id)
    {
        $execucao = Execucao::find($id);

        if (!$execucao) {
            return $this->erro('Execução não encontrada.', 404);
        }

        return $this->sucesso([
            'execucao' => $execucao,
            'resumo_microclima' => Execucao::resumoMicroclima($execucao->id_execucao),
            'total_logs_navegacao' => $execucao->logsNavegacao()->count(),
            'total_eventos' => $execucao->eventosDeteccao()->count(),
        ]);
    }

    public function store(StoreExecucaoRequest $request)
    {
        if (!Veiculo::where('id_veiculo', $request->id_veiculo)->exists()) {
            return $this->erro('Veículo informado não existe.', 404);
        }

        /** @var Usuario $usuario */
        $usuario = $request->attributes->get('usuario_api');

        $execucao = Execucao::create([
            'id_usuario' => $request->input('id_usuario', $usuario->id_usuario),
            'id_veiculo' => (int) $request->id_veiculo,
            'data_execucao' => $request->input('data_execucao', now()),
            'local_execucao' => $request->local_execucao,
            'status_execucao' => $request->input('status_execucao', 'em_andamento'),
        ]);

        return $this->sucesso($execucao, 'Execução iniciada com sucesso.', 201);
    }

    public function update(UpdateExecucaoRequest $request, $id)
    {
        $execucao = Execucao::find($id);

        if (!$execucao) {
            return $this->erro('Execução não encontrada.', 404);
        }

        $execucao->fill($request->only(['local_execucao', 'status_execucao', 'data_execucao']));
        $execucao->save();

        return $this->sucesso($execucao, 'Execução atualizada com sucesso.');
    }

    public function mapaMicroclima($id)
    {
        $execucao = Execucao::find($id);

        if (!$execucao) {
            return $this->erro('Execução não encontrada.', 404);
        }

        $pontos = $execucao->dadosMicroclimaticos()
            ->orderBy('data_hora')
            ->get();

        return $this->sucesso([
            'id_execucao' => $execucao->id_execucao,
            'resumo' => Execucao::resumoMicroclima($execucao->id_execucao),
            'pontos' => $pontos,
        ], 'Dados para mapa microclimático.');
    }
}
