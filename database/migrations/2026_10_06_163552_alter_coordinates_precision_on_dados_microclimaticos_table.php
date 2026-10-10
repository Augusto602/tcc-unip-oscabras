<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterCoordinatesPrecisionOnDadosMicroclimaticosTable extends Migration
{
    public function up()
    {
        DB::statement("
            ALTER TABLE dados_microclimaticos
            MODIFY latitude_X DECIMAL(10,6) NOT NULL,
            MODIFY longitude_Y DECIMAL(10,6) NOT NULL
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE dados_microclimaticos
            MODIFY latitude_X FLOAT NOT NULL,
            MODIFY longitude_Y FLOAT NOT NULL
        ");
    }
}
