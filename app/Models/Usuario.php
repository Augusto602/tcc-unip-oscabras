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
    ];

    protected $hidden = [
        'senha_hash',
    ];

    public function veiculos()
    {
        return $this->hasMany(
            Veiculo::class,
            'id_usuario',
            'id_usuario'
        );
    }

    public function execucoes()
    {
        return $this->hasMany(
            Execucao::class,
            'id_usuario',
            'id_usuario'
        );
    }

    public function emitirToken(): string
    {
        return Str::random(60);
    }

    public function paraResposta(): array
    {
        return [
            'id_usuario' => $this->id_usuario,
            'nome_usuario' => $this->nome_usuario,
            'email' => $this->email,
            'data_cadastro' => $this->data_cadastro,
        ];
    }
}