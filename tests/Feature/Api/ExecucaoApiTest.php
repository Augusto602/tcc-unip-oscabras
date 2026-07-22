<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CriaDadosTeste;
use Tests\TestCase;

class ExecucaoApiTest extends TestCase
{
    use RefreshDatabase;
    use CriaDadosTeste;

    public function test_criar_execucao()
    {
        [, $token] = $this->autenticarApi();
        $veiculo = $this->criarVeiculo();

        $response = $this->postJson('/api/execucoes', [
            'id_veiculo' => $veiculo->id_veiculo,
            'local_execucao' => 'Setor Norte',
            'status_execucao' => 'em_andamento',
        ], $this->headersComToken($token));

        $response->assertStatus(201)
            ->assertJsonPath('sucesso', true)
            ->assertJsonPath('dados.local_execucao', 'Setor Norte')
            ->assertJsonPath('dados.status_execucao', 'em_andamento');
    }

    public function test_atualizar_status_da_execucao()
    {
        [, $token] = $this->autenticarApi();
        $execucao = $this->criarExecucao();

        $response = $this->putJson('/api/execucoes/' . $execucao->id_execucao, [
            'status_execucao' => 'concluida',
        ], $this->headersComToken($token));

        $response->assertStatus(200)
            ->assertJsonPath('dados.status_execucao', 'concluida');
    }

    public function test_mapa_microclima()
    {
        [, $token] = $this->autenticarApi();
        $execucao = $this->criarExecucao();
        $this->criarDadoMicroclimatico($execucao, ['temperatura' => 29.0, 'umidade' => 70.0]);

        $response = $this->getJson(
            '/api/execucoes/' . $execucao->id_execucao . '/mapa-microclima',
            $this->headersComToken($token)
        );

        $response->assertStatus(200)
            ->assertJsonPath('dados.id_execucao', $execucao->id_execucao)
            ->assertJsonPath('dados.resumo.total_registros', 1);

        $this->assertCount(1, $response->json('dados.pontos'));
    }

    public function test_listar_execucoes()
    {
        [, $token] = $this->autenticarApi();
        $this->criarExecucao();
        $this->criarExecucao();

        $response = $this->getJson('/api/execucoes', $this->headersComToken($token));

        $response->assertStatus(200);
        $this->assertGreaterThanOrEqual(2, count($response->json('dados')));
    }
}
