<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupuestoMercadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supuesto_mercados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('valor_renta');
            $table->string('costo_mantenimiento');
            $table->string('costo_cierre');
            $table->string('inflacion');
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
        Schema::dropIfExists('supuesto_mercados');
    }
}
