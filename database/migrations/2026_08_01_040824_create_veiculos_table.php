<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVeiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('veiculos', function (Blueprint $table) {

            $table->id('id_veiculo');

            $table->unsignedInteger('id_usuario');

            $table->string('nome_veiculo');
            $table->string('tipo_modelo');
            $table->string('fabricante')->nullable();
            $table->text('especificacoes_hardware')->nullable();

            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veiculos');
    }
}