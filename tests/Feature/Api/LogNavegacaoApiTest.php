<?php

namespace Tests\Feature\Api;

use App\Models\LogNavegacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CriaDadosTeste;
use Tests\TestCase;

class LogNavegacaoApiTest extends TestCase
{
    use RefreshDatabase;
    use CriaDadosTeste;

    public function test_salvar_rota_realizada()
    {
        [, $token] = $this->autenticarApi();
        $execucao = $this->criarExecucao();

        $response = $this->postJson('/api/logs-navegacao', [
            'id_execucao' => $execucao->id_execucao,
            'distancia_percorrida' => 55.5,
            'tempo_decorrido' => '00:25:00',
            'velocidade_media' => 0.05,
            'latitude' => -23.3215,
            'longitude' => -46.7290,
        ], $this->headersComToken($token));

        $response->assertStatus(201)
            ->assertJsonPath('sucesso', true)
            ->assertJsonPath('dados.distancia_percorrida', 55.5);

        $this->assertDatabaseHas('logs_navegacao', [
            'id_execucao' => $execucao->id_execucao,
            'distancia_percorrida' => 55.5,
        ]);
    }

    public function test_nao_aceita_distancia_negativa()
    {
        [, $token] = $this->autenticarApi();
        $execucao = $this->criarExecucao();

        $this->postJson('/api/logs-navegacao', [
            'id_execucao' => $execucao->id_execucao,
            'distancia_percorrida' => -10,
            'tempo_decorrido' => '00:01:00',
            'velocidade_media' => 0.1,
        ], $this->headersComToken($token))->assertStatus(422);
    }

    public function test_listar_logs_por_execucao()
    {
        [, $token] = $this->autenticarApi();
        $execucao = $this->criarExecucao();

        LogNavegacao::create([
            'id_execucao' => $execucao->id_execucao,
            'data_hora' => now(),
            'distancia_percorrida' => 10,
            'tempo_decorrido' => '00:05:00',
            'velocidade_media' => 0.03,
            'latitude' => -23.5,
            'longitude' => -46.6,
        ]);

        $response = $this->getJson(
            '/api/logs-navegacao?id_execucao=' . $execucao->id_execucao,
            $this->headersComToken($token)
        );

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('dados'));
    }

    public function test_salvar_lote_de_rotas()
    {
        [, $token] = $this->autenticarApi();
        $execucao = $this->criarExecucao();

        $response = $this->postJson('/api/logs-navegacao/lote', [
            'registros' => [
                [
                    'id_execucao' => $execucao->id_execucao,
                    'distancia_percorrida' => 10,
                    'tempo_decorrido' => '00:05:00',
                    'velocidade_media' => 0.03,
                ],
                [
                    'id_execucao' => $execucao->id_execucao,
                    'distancia_percorrida' => 20,
                    'tempo_decorrido' => '00:10:00',
                    'velocidade_media' => 0.04,
                ],
            ],
        ], $this->headersComToken($token));

        $response->assertStatus(201)
            ->assertJsonPath('dados.total', 2);

        $this->assertEquals(2, LogNavegacao::where('id_execucao', $execucao->id_execucao)->count());
    }
}
