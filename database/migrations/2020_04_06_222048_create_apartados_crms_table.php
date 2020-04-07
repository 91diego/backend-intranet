<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApartadosCrmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartados_crms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_negociacion');
            $table->integer('id_lead');
            $table->string('nombre_negociacion');
            $table->string('producto1');
            $table->string('producto2');
            $table->string('total');
            $table->string('precio_producto');
            $table->integer('estatus_apartado');
            $table->string('desarrollo');
            $table->foreign('id_responsable')->references('id_usuario_crm')->on('usuarios');
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
        Schema::dropIfExists('apartados_crms');
    }
}
