<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupuestoComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supuesto_compras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('inicio_plazo');
            $table->string('fin_plazo');
            $table->string('tipo_compra');
            $table->unsignedInteger('desarrollo_id');
            $table->foreign('desarrollo_id')->references('id')->on('desarrollos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supuesto_compras');
    }
}
