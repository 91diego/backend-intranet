<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespuestaSolicitudPermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respuesta_solicitud_permisos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('comentarios');
            $table->unsignedInteger('id_catalogo_estatus');
            $table->unsignedInteger('id_solicitud_permisos');
            $table->foreign('id_catalogo_estatus')->references('id')->on('catalogo_estatus');
            $table->foreign('id_catalogo_estatus')->references('id')->on('solicitud_permisos');
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
        Schema::dropIfExists('respuesta_solicitud_permisos');
    }
}
