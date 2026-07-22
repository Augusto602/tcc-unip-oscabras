<?php

namespace Tests\Feature\Api;

use App\Models\DadoMicroclimatico;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CriaDadosTeste;
use Tests\TestCase;

class DadoMicroclimaticoApiTest extends TestCase
{
    use RefreshDatabase;
    use CriaDadosTeste;

    public function test_salvar_temperatura_e_umidade()
    {
        [, $token] = $this->autenticarApi();
        $execucao = $this->criarExecucao();

        $response = $this->postJson('/api/dados-microclimaticos', [
            'id_execucao' => $execucao->id_execucao,
            'temperatura' => 27.4,
            'umidade' => 63.2,
            'latitude' => -23.32145,
            'longitude' => -46.72891,
        ], $this->headersComToken($token));

        $response->assertStatus(201)
            ->assertJsonPath('sucesso', true)
            ->assertJsonPath('dados.temperatura', 27.4)
            ->assertJsonPath('dados.umidade', 63.2);

        $this->assertDatabaseHas('dados_microclimaticos', [
            'id_execucao' => $execucao->id_execucao,
            'temperatura' => 27.4,
            'umidade' => 63.2,
        ]);
    }

    public function test_nao_salva_sem_token()
    {
        $execucao = $this->criarExecucao();

        $this->postJson('/api/dados-microclimaticos', [
            'id_execucao' => $execucao->id_execucao,
            'temperatura' => 27.4,
            'umidade' => 63.2,
            'latitude' => -23.32,
            'longitude' => -46.72,
        ])->assertStatus(401);
    }

    public function test_valida_faixa_de_temperatura()
    {
        [, $token] = $this->autenticarApi();
        $execucao = $this->criarExecucao();

        $this->postJson('/api/dados-microclimaticos', [
            'id_execucao' => $execucao->id_execucao,
            'temperatura' => 999,
            'umidade' => 50,
            'latitude' => -23.32,
            'longitude' => -46.72,
        ], $this->headersComToken($token))->assertStatus(422);
    }

    public function test_listar_dados_por_execucao()
    {
        [, $token] = $this->autenticarApi();
        $execucao = $this->criarExecucao();
        $this->criarDadoMicroclimatico($execucao, ['temperatura' => 22.0]);
        $this->criarDadoMicroclimatico($execucao, ['temperatura' => 28.0]);

        $response = $this->getJson(
            '/api/dados-microclimaticos?id_execucao=' . $execucao->id_execucao,
            $this->headersComToken($token)
        );

        $response->assertStatus(200)
            ->assertJsonPath('sucesso', true);

        $this->assertCount(2, $response->json('dados'));
    }

    public function test_salvar_lote()
    {
        [, $token] = $this->autenticarApi();
        $execucao = $this->criarExecucao();

        $response = $this->postJson('/api/dados-microclimaticos/lote', [
            'registros' => [
                [
                    'id_execucao' => $execucao->id_execucao,
                    'temperatura' => 25.0,
                    'umidade' => 55.0,
                    'latitude' => -23.1,
                    'longitude' => -46.1,
                ],
                [
                    'id_execucao' => $execucao->id_execucao,
                    'temperatura' => 26.0,
                    'umidade' => 56.0,
                    'latitude' => -23.2,
                    'longitude' => -46.2,
                ],
            ],
        ], $this->headersComToken($token));

        $response->assertStatus(201)
            ->assertJsonPath('dados.total', 2);

        $this->assertEquals(2, DadoMicroclimatico::where('id_execucao', $execucao->id_execucao)->count());
    }
}
