<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicoesTable extends Migration
{
    public function up()
    {
        Schema::create('medicoes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $key = $table->string('idempotency_key', 128);
            if (Schema::getConnection()->getDriverName() === 'mysql') {
                $key->collation('utf8mb4_bin');
            }
            $table->unique('idempotency_key');
            $table->double('x');
            $table->double('y');
            $table->double('z');
            $table->double('umidade');
            $table->double('temperatura');
            $table->timestamp('recebida_em');
        });
    }

    public function down()
    {
        Schema::dropIfExists('medicoes');
    }
}
