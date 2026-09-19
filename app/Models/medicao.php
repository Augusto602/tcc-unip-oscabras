<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicao extends Model
{
    protected $connection = 'mysql_monitoramento';

    protected $table = 'medicoes';

    protected $fillable = [
        'idempotency_key',
        'x',
        'y',
        'z',
        'umidade',
        'temperatura',
        'recebida_em'
    ];

    public $timestamps = false;
}
