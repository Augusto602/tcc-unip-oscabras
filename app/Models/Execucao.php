<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Execucao extends Model
{
    public const STATUS = ['em_andamento', 'concluida', 'abortada', 'falha'];

    protected $table = 'execucoes';

    protected $primaryKey = 'id_execucao';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_veiculo',
        'data_execucao',
        'local_execucao',
        'status_execucao',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'id_veiculo', 'id_veiculo');
    }

    public function dadosMicroclimaticos()
    {
        return $this->hasMany(DadoMicroclimatico::class, 'id_execucao', 'id_execucao');
    }

    public function eventosDeteccao()
    {
        return $this->hasMany(EventoDeteccao::class, 'id_execucao', 'id_execucao');
    }

    public function logsNavegacao()
    {
        return $this->hasMany(LogNavegacao::class, 'id_execucao', 'id_execucao');
    }

    public static function resumoMicroclima(?int $idExecucao = null): array
    {
        $query = DadoMicroclimatico::query();

        if ($idExecucao !== null) {
            $query->where('id_execucao', $idExecucao);
        }

        $dados = $query->get();

        if ($dados->isEmpty()) {
            return [
                'temperatura_media' => 0,
                'umidade_media' => 0,
                'total_registros' => 0,
                'ultimo' => null,
            ];
        }

        return [
            'temperatura_media' => round($dados->avg('temperatura'), 1),
            'umidade_media' => round($dados->avg('umidade'), 1),
            'total_registros' => $dados->count(),
            'ultimo' => $dados->sortByDesc('data_hora')->first(),
        ];
    }
}
