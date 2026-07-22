<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventoDeteccao extends Model
{
    public const TIPOS = ['obstaculo', 'parada_emergencial', 'desvio', 'falha', 'alerta'];

    protected $table = 'eventos_deteccao';

    protected $primaryKey = 'id_evento';

    public $timestamps = false;

    protected $fillable = [
        'id_execucao',
        'data_hora',
        'tipo_evento',
        'tipo_obstaculo',
        'score_confianca',
    ];

    public function execucao()
    {
        return $this->belongsTo(Execucao::class, 'id_execucao', 'id_execucao');
    }
}
