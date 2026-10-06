<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('eventos_deteccao', function (Blueprint $table) {

            $table->id('id_evento');

            $table->unsignedBigInteger('id_execucao');

            $table->dateTime('data_hora');

            $table->string('tipo_evento');

            $table->string('tipo_obstaculo')->nullable();

            $table->float('score_confianca')->nullable();

            $table->foreign('id_execucao')
                ->references('id_execucao')
                ->on('execucoes');

        });
    }

    public function down()
    {
        Schema::dropIfExists('eventos_deteccao');
    }
};