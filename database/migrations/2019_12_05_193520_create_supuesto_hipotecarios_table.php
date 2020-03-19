<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupuestoHipotecariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supuesto_hipotecarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('porcentaje_comision_apertura');
            $table->string('porcentaje_enganche');
            $table->string('duracion_credito');
            $table->string('tasa_interes');
            $table->string('tasa_extra');
            $table->string('repago_capital');
            $table->string('porcentaje_descuento');
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
        Schema::dropIfExists('supuesto_hipotecarios');
    }
}
