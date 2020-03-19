<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudPermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_permisos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('folio');
            $table->string('fecha_solicitud');
            $table->string('fecha_inicio');
            $table->string('fecha_fin');
            $table->string('motivo_permiso');
            $table->unsignedInteger('id_catalogo_motivo_permisos');
            $table->unsignedInteger('id_usuarios');
            $table->foreign('id_catalogo_motivo_permisos')->references('id')->on('catalogo_motivo_permisos');
            $table->foreign('id_usuarios')->references('id')->on('usuarios');
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
        Schema::dropIfExists('solicitud_permisos');
    }
}
