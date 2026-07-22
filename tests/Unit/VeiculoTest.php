<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CriaDadosTeste;
use Tests\TestCase;

class VeiculoTest extends TestCase
{
    use RefreshDatabase;
    use CriaDadosTeste;

    public function test_sem_execucao_fica_offline_e_status_padrao()
    {
        $veiculo = $this->criarVeiculo();

        $this->assertEquals('sem_execucao', $veiculo->status_execucao);
        $this->assertEquals('—', $veiculo->local_execucao);
        $this->assertFalse($veiculo->online);
    }

    public function test_com_execucao_em_andamento_fica_online()
    {
        $veiculo = $this->criarVeiculo();
        $this->criarExecucao($veiculo, [
            'status_execucao' => 'em_andamento',
            'local_execucao' => 'Estufa',
            'data_execucao' => now(),
        ]);

        $veiculo = $veiculo->fresh()->load('execucoes');

        $this->assertEquals('em_andamento', $veiculo->status_execucao);
        $this->assertEquals('Estufa', $veiculo->local_execucao);
        $this->assertTrue($veiculo->online);
    }

    public function test_com_execucao_abortada_fica_offline()
    {
        $veiculo = $this->criarVeiculo();
        $this->criarExecucao($veiculo, [
            'status_execucao' => 'abortada',
            'data_execucao' => now(),
        ]);

        $veiculo = $veiculo->fresh()->load('execucoes');

        $this->assertEquals('abortada', $veiculo->status_execucao);
        $this->assertFalse($veiculo->online);
    }

    public function test_usa_ultima_execucao_por_data()
    {
        $veiculo = $this->criarVeiculo();

        $this->criarExecucao($veiculo, [
            'status_execucao' => 'concluida',
            'local_execucao' => 'Antiga',
            'data_execucao' => now()->subDay(),
        ]);

        $this->criarExecucao($veiculo, [
            'status_execucao' => 'em_andamento',
            'local_execucao' => 'Nova',
            'data_execucao' => now(),
        ]);

        $veiculo = $veiculo->fresh()->load('execucoes');

        $this->assertEquals('em_andamento', $veiculo->status_execucao);
        $this->assertEquals('Nova', $veiculo->local_execucao);
        $this->assertTrue($veiculo->online);
    }

    public function test_pertence_a_usuario()
    {
        $usuario = $this->criarUsuario();
        $veiculo = $this->criarVeiculo($usuario);

        $this->assertEquals($usuario->id_usuario, $veiculo->usuario->id_usuario);
    }
}
