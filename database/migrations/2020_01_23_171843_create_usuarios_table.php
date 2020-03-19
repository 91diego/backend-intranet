<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email');
            $table->string('empresa');
            $table->string('puesto');
            $table->string('estatus');
            $table->integer('id_usuario_crm');
            $table->integer('id_vacaciones');
            $table->unsignedInteger('id_depto_crm');
            $table->foreign('id_depto_crm')->references('id')->on('catalogo_departamentos');
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
        Schema::dropIfExists('usuarios');
    }
}
