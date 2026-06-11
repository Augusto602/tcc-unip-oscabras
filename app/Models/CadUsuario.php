<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CadUsuario extends Model
{
    protected $table = 'cad_usuarios';

    protected $primaryKey = 'cad_usuario_id';

    public $timestamps = false;

    protected $fillable = [
        'cad_usuario_nome',
        'cad_usuario_ativo_sn',
        'cad_usuario_dt_cad',
        'cad_usuario_usu_cad',
        'cad_usuario_tipo_login',
        'cad_usuario_senha',
        'cad_usuario_email',
    ];

    protected $hidden = [
        'cad_usuario_senha',
    ];
}