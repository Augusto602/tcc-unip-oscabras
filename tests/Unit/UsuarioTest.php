<?php

namespace Tests\Unit;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Support\CriaDadosTeste;
use Tests\TestCase;

class UsuarioTest extends TestCase
{
    use RefreshDatabase;
    use CriaDadosTeste;

    public function test_pode_criar_usuario()
    {
        $usuario = $this->criarUsuario([
            'nome_usuario' => 'Ana',
            'email' => 'ana@teste.com',
        ]);

        $this->assertDatabaseHas('usuarios', [
            'id_usuario' => $usuario->id_usuario,
            'email' => 'ana@teste.com',
            'nome_usuario' => 'Ana',
        ]);
    }

    public function test_emitir_token_gera_e_persiste_api_token()
    {
        $usuario = $this->criarUsuario();

        $token = $usuario->emitirToken();

        $this->assertNotEmpty($token);
        $this->assertEquals(60, strlen($token));
        $this->assertDatabaseHas('usuarios', [
            'id_usuario' => $usuario->id_usuario,
            'api_token' => $token,
        ]);
    }

    public function test_revogar_token_limpa_api_token()
    {
        $usuario = $this->criarUsuario();
        $usuario->emitirToken();

        $usuario->revogarToken();

        $this->assertNull($usuario->fresh()->api_token);
    }

    public function test_para_resposta_nao_expoe_senha_nem_token()
    {
        $usuario = $this->criarUsuario([
            'nome_usuario' => 'Bruno',
            'email' => 'bruno@teste.com',
            'tipo_login' => 'CEO',
        ]);
        $usuario->emitirToken();

        $resposta = $usuario->fresh()->paraResposta();

        $this->assertEquals('Bruno', $resposta['nome_usuario']);
        $this->assertEquals('bruno@teste.com', $resposta['email']);
        $this->assertArrayNotHasKey('senha_hash', $resposta);
        $this->assertArrayNotHasKey('api_token', $resposta);
    }

    public function test_senha_e_armazenada_com_hash()
    {
        $usuario = $this->criarUsuario([
            'senha_hash' => Hash::make('segredo'),
        ]);

        $this->assertTrue(Hash::check('segredo', $usuario->senha_hash));
        $this->assertNotEquals('segredo', $usuario->senha_hash);
    }

    public function test_relacionamento_com_veiculos()
    {
        $usuario = $this->criarUsuario();
        $this->criarVeiculo($usuario);
        $this->criarVeiculo($usuario);

        $this->assertCount(2, $usuario->veiculos);
    }
}
