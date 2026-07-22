<?php

namespace Tests\Feature\Api;

use App\Models\DadoMicroclimatico;
use App\Models\EventoDeteccao;
use App\Models\LogNavegacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CriaDadosTeste;
use Tests\TestCase;

class SyncTelemetriaApiTest extends TestCase
{
    use RefreshDatabase;
    use CriaDadosTeste;

    public function test_sync_telemetria_formato_tcc()
    {
        [, $token] = $this->autenticarApi();
        $execucao = $this->criarExecucao();

        $response = $this->postJson('/api/sync/telemetria', [
            'id_execucao' => $execucao->id_execucao,
            'timestamp' => '2026-05-14T14:30:00',
            'temperatura' => 27.4,
            'umidade' => 63.2,
            'latitude' => -23.32145,
            'longitude' => -46.72891,
            'obstaculo_detectado' => true,
            'acao_executada' => 'desvio_direita',
            'score_confianca' => 0.9,
            'distancia_percorrida' => 12.5,
            'tempo_decorrido' => '00:05:00',
            'velocidade_media' => 0.04,
        ], $this->headersComToken($token));

        $response->assertStatus(201)
            ->assertJsonPath('sucesso', true)
            ->assertJsonPath('dados.microclima.temperatura', 27.4)
            ->assertJsonPath('dados.evento_deteccao.tipo_evento', 'desvio');

        $this->assertEquals(1, DadoMicroclimatico::count());
        $this->assertEquals(1, LogNavegacao::count());
        $this->assertEquals(1, EventoDeteccao::count());
    }

    public function test_sync_sem_obstaculo_nao_cria_evento()
    {
        [, $token] = $this->autenticarApi();
        $execucao = $this->criarExecucao();

        $this->postJson('/api/sync/telemetria', [
            'id_execucao' => $execucao->id_execucao,
            'temperatura' => 25.0,
            'umidade' => 50.0,
            'latitude' => -23.3,
            'longitude' => -46.7,
            'obstaculo_detectado' => false,
        ], $this->headersComToken($token))->assertStatus(201);

        $this->assertEquals(1, DadoMicroclimatico::count());
        $this->assertEquals(0, EventoDeteccao::count());
    }
}
