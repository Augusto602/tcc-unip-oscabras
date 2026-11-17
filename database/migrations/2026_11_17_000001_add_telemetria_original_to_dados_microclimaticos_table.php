<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTelemetriaOriginalToDadosMicroclimaticosTable extends Migration
{
    public function up()
    {
        Schema::table('dados_microclimaticos', function (Blueprint $table) {
            $table->json('telemetria_original')->nullable();
        });
    }

    public function down()
    {
        Schema::table('dados_microclimaticos', function (Blueprint $table) {
            $table->dropColumn('telemetria_original');
        });
    }
}
