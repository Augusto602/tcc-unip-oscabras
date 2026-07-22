<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogNavegacao extends Model
{
    protected $table = 'logs_navegacao';

    protected $primaryKey = 'id_log';

    public $timestamps = false;

    protected $fillable = [
        'id_execucao',
        'data_hora',
        'distancia_percorrida',
        'tempo_decorrido',
        'velocidade_media',
        'latitude',
        'longitude',
    ];

    public function execucao()
    {
        return $this->belongsTo(Execucao::class, 'id_execucao', 'id_execucao');
    }
}
