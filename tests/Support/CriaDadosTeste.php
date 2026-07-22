<?php

namespace Tests\Support;

use App\Models\DadoMicroclimatico;
use App\Models\Execucao;
use App\Models\Usuario;
use App\Models\Veiculo;
use Illuminate\Support\Facades\Hash;

trait CriaDadosTeste
{
    protected function criarUsuario(array $overrides = []): Usuario
    {
        return Usuario::create(array_merge([
            'nome_usuario' => 'Usuario Teste',
            'email' => 'teste_' . uniqid() . '@oscabras.com',
            'senha_hash' => Hash::make('123456'),
            'data_cadastro' => now(),
            'tipo_login' => 'Cliente',
        ], $overrides));
    }

    protected function autenticarApi(?Usuario $usuario = null): array
    {
        $usuario = $usuario ?: $this->criarUsuario([
            'email' => 'admin_api@oscabras.com',
            'senha_hash' => Hash::make('123456'),
        ]);

        $token = $usuario->emitirToken();

        return [$usuario, $token];
    }

    protected function criarVeiculo(?Usuario $usuario = null, array $overrides = []): Veiculo
    {
        $usuario = $usuario ?: $this->criarUsuario();

        return Veiculo::create(array_merge([
            'id_usuario' => $usuario->id_usuario,
            'nome_veiculo' => 'Carrinho ' . uniqid(),
            'tipo_modelo' => 'AGV-Agro Mini',
            'fabricante' => 'Oscabras',
            'especificacoes_hardware' => 'ESP32, DHT22',
        ], $overrides));
    }

    protected function criarExecucao(?Veiculo $veiculo = null, array $overrides = []): Execucao
    {
        $veiculo = $veiculo ?: $this->criarVeiculo();

        return Execucao::create(array_merge([
            'id_usuario' => $veiculo->id_usuario,
            'id_veiculo' => $veiculo->id_veiculo,
            'data_execucao' => now(),
            'local_execucao' => 'Área de Teste',
            'status_execucao' => 'em_andamento',
        ], $overrides));
    }

    protected function criarDadoMicroclimatico(?Execucao $execucao = null, array $overrides = []): DadoMicroclimatico
    {
        $execucao = $execucao ?: $this->criarExecucao();

        return DadoMicroclimatico::create(array_merge([
            'id_execucao' => $execucao->id_execucao,
            'data_hora' => now(),
            'temperatura' => 27.5,
            'umidade' => 60.0,
            'latitude' => -23.55,
            'longitude' => -46.63,
        ], $overrides));
    }

    protected function headersComToken(string $token): array
    {
        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
    }
}
