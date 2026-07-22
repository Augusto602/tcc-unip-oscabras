<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DadoMicroclimatico extends Model
{
    protected $table = 'dados_microclimaticos';

    protected $primaryKey = 'id_dado';

    public $timestamps = false;

    protected $fillable = [
        'id_execucao',
        'data_hora',
        'temperatura',
        'umidade',
        'latitude',
        'longitude',
    ];

    public function execucao()
    {
        return $this->belongsTo(Execucao::class, 'id_execucao', 'id_execucao');
    }
}
