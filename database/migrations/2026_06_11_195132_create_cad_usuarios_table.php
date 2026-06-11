<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCadUsuariosTable extends Migration
{
    public function up()
    {
        Schema::create('cad_usuarios', function (Blueprint $table) {
            $table->increments('cad_usuario_id');
            $table->string('cad_usuario_nome');
            $table->char('cad_usuario_ativo_sn', 1)->default('S');
            $table->dateTime('cad_usuario_dt_cad')->nullable();
            $table->string('cad_usuario_usu_cad')->nullable();
            $table->string('cad_usuario_tipo_login', 50);
            $table->string('cad_usuario_senha', 255);
            $table->string('cad_usuario_email')->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cad_usuarios');
    }
}