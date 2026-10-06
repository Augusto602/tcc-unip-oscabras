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

            $table->date('data_execucao');

            $table->string('local_execucao');

            $table->enum('status_execucao', [
                'em_andamento',
                'concluida',
                'abortada',
                'falha'
            ]);

            $table->string('nome_execucao', 100)->unique();

            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios');

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