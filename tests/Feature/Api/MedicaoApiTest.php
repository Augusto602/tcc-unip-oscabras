<?php

namespace Tests\Feature\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MedicaoApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Este contrato não depende das tabelas legadas de usuários/execuções.
        require_once database_path('migrations/2026_09_13_000000_create_medicoes_table.php');
        (new \CreateMedicoesTable)->up();
    }

    private function enviar(array $dados = [], array $headers = [])
    {
        return $this->call('POST', '/Apidados', [], [], [], array_merge([
            'CONTENT_TYPE' => 'application/json',
            'HTTP_IDEMPOTENCY_KEY' => 'medicao-001',
        ], $headers), json_encode(array_merge([
            'x' => 0.04, 'y' => 0.0, 'z' => 0.15, 'umidade' => 64.0, 'temperatura' => 25.0,
        ], $dados)));
    }

    public function test_salva_e_confirma_reenvio_sem_alterar_medicao()
    {
        $this->enviar()->assertStatus(201)->assertExactJson(['sucesso' => true, 'mensagem' => 'Medição salva']);
        $this->enviar(['x' => 9])->assertStatus(200)->assertJsonPath('sucesso', true);
        $this->assertSame(1, DB::table('medicoes')->count());
        $this->assertDatabaseHas('medicoes', ['idempotency_key' => 'medicao-001', 'x' => 0.04, 'umidade' => 64]);
        $this->enviar([], ['HTTP_IDEMPOTENCY_KEY' => 'medicao-002'])->assertStatus(201);
        $this->assertSame(2, DB::table('medicoes')->count());
    }

    public function test_salva_sem_autenticacao()
    {
        $this->enviar()->assertStatus(201)->assertJsonPath('sucesso', true);
        $this->assertSame(1, DB::table('medicoes')->count());
    }

    public function test_campos_e_chave_invalidos_retornam_json_sem_accept()
    {
        foreach (['x', 'y', 'z', 'umidade', 'temperatura'] as $campo) {
            foreach ([null, true, '12', []] as $valor) {
                $this->enviar([$campo => $valor])->assertStatus(422)->assertJsonPath('sucesso', false);
            }
        }
        $this->enviar(['umidade' => 101])->assertStatus(422);
        $this->enviar(['umidade' => -1])->assertStatus(422);
        foreach (['', 'com espaço', str_repeat('a', 129)] as $key) {
            $this->enviar([], ['HTTP_IDEMPOTENCY_KEY' => $key])->assertStatus(422);
        }
        $this->assertSame(0, DB::table('medicoes')->count());
    }

    public function test_json_malformado_e_tipo_de_conteudo()
    {
        $this->call('POST', '/Apidados', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], '{')->assertStatus(422)->assertJsonPath('sucesso', false);
        $this->enviar([], ['CONTENT_TYPE' => 'text/plain'])->assertStatus(415);
    }

    public function test_falha_de_persistencia_retorna_500()
    {
        Schema::drop('medicoes');
        $this->enviar()->assertStatus(500)->assertExactJson([
            'sucesso' => false, 'mensagem' => 'Falha ao salvar medição.',
        ]);
    }

    public function test_falha_no_insert_nao_confirma_e_permite_reenvio()
    {
        DB::unprepared("CREATE TRIGGER falhar_medicao BEFORE INSERT ON medicoes BEGIN SELECT RAISE(ABORT, 'falha simulada'); END");
        $this->enviar()->assertStatus(500)->assertJsonPath('sucesso', false);
        $this->assertSame(0, DB::table('medicoes')->count());
        DB::unprepared('DROP TRIGGER falhar_medicao');
        $this->enviar()->assertStatus(201)->assertJsonPath('sucesso', true);
    }

    public function test_campos_ausentes_sao_rejeitados()
    {
        $this->call('POST', '/Apidados', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_IDEMPOTENCY_KEY' => 'medicao-001',
        ], '{}')->assertStatus(422)->assertJsonValidationErrors(['x', 'y', 'z', 'umidade', 'temperatura'], 'erros');
    }

    public function test_indice_unico_impede_insercao_duplicada()
    {
        $this->enviar()->assertStatus(201);
        $registro = (array) DB::table('medicoes')->first();
        unset($registro['id']);
        $this->expectException(\Illuminate\Database\QueryException::class);
        DB::table('medicoes')->insert($registro);
    }
}
