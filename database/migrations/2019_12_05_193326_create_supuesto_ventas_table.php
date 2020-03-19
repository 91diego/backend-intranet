<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupuestoVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supuesto_ventas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('porcentaje_firma');
            $table->string('porcentaje_plazo');
            $table->string('meses_plazo');
            $table->string('porcentaje_escritura');
            $table->string('porcentaje_descuento');
            $table->string('porcentaje_rendimiento');
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
        Schema::dropIfExists('supuesto_ventas');
    }
}
