<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExecucoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('execucoes', function (Blueprint $table) {

            $table->id('id_execucao');

            $table->unsignedInteger('id_usuario');

            $table->unsignedBigInteger('id_veiculo');

            $table->dateTime('data_execucao');

            $table->string('local_execucao');

            $table->enum('status_execucao', [
                'em_andamento',
                'concluida',
                'abortada',
                'falha'
            ]);

            $table->foreign('id_usuario')
                ->references('cad_usuario_id')
                ->on('cad_usuarios');

            $table->foreign('id_veiculo')
                ->references('id_veiculo')
                ->on('veiculos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('execucoes');
    }
}
