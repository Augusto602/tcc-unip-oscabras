<?php

namespace Tests\Unit;

use App\Models\Execucao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CriaDadosTeste;
use Tests\TestCase;

class ExecucaoTest extends TestCase
{
    use RefreshDatabase;
    use CriaDadosTeste;

    public function test_constantes_de_status_do_tcc()
    {
        $this->assertEquals(
            ['em_andamento', 'concluida', 'abortada', 'falha'],
            Execucao::STATUS
        );
    }

    public function test_resumo_microclima_vazio()
    {
        $resumo = Execucao::resumoMicroclima();

        $this->assertEquals(0, $resumo['temperatura_media']);
        $this->assertEquals(0, $resumo['umidade_media']);
        $this->assertEquals(0, $resumo['total_registros']);
        $this->assertNull($resumo['ultimo']);
    }

    public function test_resumo_microclima_calcula_medias()
    {
        $execucao = $this->criarExecucao();

        $this->criarDadoMicroclimatico($execucao, [
            'temperatura' => 20.0,
            'umidade' => 40.0,
            'data_hora' => now()->subHour(),
        ]);

        $this->criarDadoMicroclimatico($execucao, [
            'temperatura' => 30.0,
            'umidade' => 60.0,
            'data_hora' => now(),
        ]);

        $resumo = Execucao::resumoMicroclima($execucao->id_execucao);

        $this->assertEquals(25.0, $resumo['temperatura_media']);
        $this->assertEquals(50.0, $resumo['umidade_media']);
        $this->assertEquals(2, $resumo['total_registros']);
        $this->assertEquals(30.0, (float) $resumo['ultimo']->temperatura);
    }

    public function test_relacionamentos_da_execucao()
    {
        $execucao = $this->criarExecucao();
        $this->criarDadoMicroclimatico($execucao);

        $this->assertNotNull($execucao->veiculo);
        $this->assertNotNull($execucao->usuario);
        $this->assertCount(1, $execucao->dadosMicroclimaticos);
    }
}
