<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Usuario extends Model
{
    protected $table = 'cad_usuarios';

    protected $primaryKey = 'cad_usuario_id';

    public $timestamps = false;

    protected $fillable = [
        'cad_usuario_nome',
        'cad_usuario_email',
        'cad_usuario_senha',
        'cad_usuario_dt_cad',
        'cad_usuario_tipo_login',
        'cad_usuario_usu_cad',
    ];

    protected $hidden = [
        'cad_usuario_senha',
    ];

    public function veiculos()
    {
        return $this->hasMany(
            Veiculo::class,
            'id_usuario',
            'cad_usuario_id'
        );
    }

    public function execucoes()
    {
        return $this->hasMany(
            Execucao::class,
            'id_usuario',
            'cad_usuario_id'
        );
    }

    public function emitirToken(): string
    {
        $token = Str::random(60);

        // Só use se criar uma coluna api_token no banco
        return $token;
    }

    public function paraResposta(): array
    {
        return [
            'id_usuario' => $this->cad_usuario_id,
            'nome_usuario' => $this->cad_usuario_nome,
            'email' => $this->cad_usuario_email,
            'data_cadastro' => $this->cad_usuario_dt_cad,
            'tipo_login' => $this->cad_usuario_tipo_login,
        ];
    }
}