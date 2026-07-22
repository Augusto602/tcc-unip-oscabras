<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    protected $table = 'veiculos';

    protected $primaryKey = 'id_veiculo';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'nome_veiculo',
        'tipo_modelo',
        'fabricante',
        'especificacoes_hardware',
    ];

    protected $appends = [
        'status_execucao',
        'local_execucao',
        'online',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function execucoes()
    {
        return $this->hasMany(Execucao::class, 'id_veiculo', 'id_veiculo');
    }

    protected function ultimaExecucaoRegistro()
    {
        if ($this->relationLoaded('execucoes')) {
            return $this->execucoes->sortByDesc('data_execucao')->first();
        }

        return $this->execucoes()->orderByDesc('data_execucao')->first();
    }

    public function getStatusExecucaoAttribute(): string
    {
        return optional($this->ultimaExecucaoRegistro())->status_execucao ?? 'sem_execucao';
    }

    public function getLocalExecucaoAttribute(): string
    {
        return optional($this->ultimaExecucaoRegistro())->local_execucao ?? '—';
    }

    public function getOnlineAttribute(): bool
    {
        return in_array($this->status_execucao, ['em_andamento', 'concluida'], true);
    }
}
