<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Support\CriaDadosTeste;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;
    use CriaDadosTeste;

    public function test_login_com_credenciais_validas_retorna_token()
    {
        $this->criarUsuario([
            'email' => 'admin@oscabras.com',
            'senha_hash' => Hash::make('123456'),
            'nome_usuario' => 'Admin',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@oscabras.com',
            'senha' => '123456',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('sucesso', true)
            ->assertJsonPath('dados.usuario.email', 'admin@oscabras.com')
            ->assertJsonStructure([
                'sucesso',
                'mensagem',
                'dados' => [
                    'usuario' => ['id_usuario', 'nome_usuario', 'email'],
                    'api_token',
                    'token_type',
                ],
            ]);

        $this->assertNotEmpty($response->json('dados.api_token'));
    }

    public function test_login_com_senha_incorreta()
    {
        $this->criarUsuario([
            'email' => 'admin@oscabras.com',
            'senha_hash' => Hash::make('123456'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@oscabras.com',
            'senha' => 'errada',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('sucesso', false);
    }

    public function test_login_usuario_inexistente()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'naoexiste@oscabras.com',
            'senha' => '123456',
        ]);

        $response->assertStatus(404)
            ->assertJsonPath('sucesso', false);
    }

    public function test_login_valida_campos_obrigatorios()
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422)
            ->assertJsonPath('sucesso', false);
    }

    public function test_me_exige_autenticacao()
    {
        $response = $this->getJson('/api/me');

        $response->assertStatus(401);
    }

    public function test_me_retorna_usuario_autenticado()
    {
        [, $token] = $this->autenticarApi(
            $this->criarUsuario(['email' => 'me@oscabras.com', 'nome_usuario' => 'Me'])
        );

        $response = $this->getJson('/api/me', $this->headersComToken($token));

        $response->assertStatus(200)
            ->assertJsonPath('dados.usuario.email', 'me@oscabras.com')
            ->assertJsonPath('dados.usuario.nome_usuario', 'Me');
    }

    public function test_logout_revoga_token()
    {
        [, $token] = $this->autenticarApi();

        $this->postJson('/api/logout', [], $this->headersComToken($token))
            ->assertStatus(200)
            ->assertJsonPath('sucesso', true);

        $this->getJson('/api/me', $this->headersComToken($token))
            ->assertStatus(401);
    }
}
