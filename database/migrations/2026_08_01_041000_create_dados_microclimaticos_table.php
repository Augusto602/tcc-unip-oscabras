<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
       Schema::create('dados_microclimaticos', function(Blueprint $table){

            $table->id('id_dado');

            $table->unsignedBigInteger('id_execucao');

            $table->dateTime('data_hora');

            $table->decimal('temperatura',5,2);

            $table->decimal('umidade',5,2);

            $table->decimal('latitude',10,7);

            $table->decimal('longitude',10,7);

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