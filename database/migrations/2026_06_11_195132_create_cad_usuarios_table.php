<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCadUsuariosTable extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id_usuario');
            $table->string('nome_usuario', 100);
            $table->string('email', 150)->unique();
            $table->string('senha_hash', 255);
            $table->dateTime('data_cadastro')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}