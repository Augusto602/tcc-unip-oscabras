<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dados_microclimaticos', function (Blueprint $table) {

            $table->id('id_dado');

            $table->unsignedBigInteger('id_execucao');

            $table->dateTime('data_hora');

            $table->float('temperatura');

            $table->float('umidade');

            $table->decimal('latitude_X', 10, 6);

            $table->decimal('longitude_Y', 10, 6);

            $table->foreign('id_execucao')
                ->references('id_execucao')
                ->on('execucoes');

        });
    }

    public function down()
    {
        Schema::dropIfExists('dados_microclimaticos');
    }
};