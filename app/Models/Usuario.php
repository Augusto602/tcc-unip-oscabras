<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $primaryKey = 'id_usuario';

    public $timestamps = false;

    protected $fillable = [
        'nome_usuario',
        'email',
        'senha_hash',
        'data_cadastro',
        'tipo_login',
        'api_token',
    ];

    protected $hidden = [
        'senha_hash',
        'api_token',
    ];

    public function veiculos()
    {
        return $this->hasMany(Veiculo::class, 'id_usuario', 'id_usuario');
    }

    public function execucoes()
    {
        return $this->hasMany(Execucao::class, 'id_usuario', 'id_usuario');
    }

    public function emitirToken(): string
    {
        $token = Str::random(60);
        $this->api_token = $token;
        $this->save();

        return $token;
    }

    public function revogarToken(): void
    {
        $this->api_token = null;
        $this->save();
    }

    public function paraResposta(): array
    {
        return [
            'id_usuario' => $this->id_usuario,
            'nome_usuario' => $this->nome_usuario,
            'email' => $this->email,
            'data_cadastro' => $this->data_cadastro,
            'tipo_login' => $this->tipo_login,
        ];
    }
}
