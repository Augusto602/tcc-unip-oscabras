<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AllowNullableSensorsOnDadosMicroclimaticosTable extends Migration
{
    public function up()
    {
        // MySQL, como a migration de precisão das coordenadas deste projeto.
        DB::statement('ALTER TABLE dados_microclimaticos MODIFY temperatura FLOAT NULL, MODIFY umidade FLOAT NULL');
    }

    public function down()
    {
        // Não substituir leituras ausentes por valores fictícios no rollback.
        if (DB::table('dados_microclimaticos')->whereNull('temperatura')->orWhereNull('umidade')->exists()) {
            throw new RuntimeException('Existem leituras sem sensores; não é possível voltar para NOT NULL sem perder essa informação.');
        }

        DB::statement('ALTER TABLE dados_microclimaticos MODIFY temperatura FLOAT NOT NULL, MODIFY umidade FLOAT NOT NULL');
    }
}
