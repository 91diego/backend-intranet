<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogoDepartamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_departamentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_depto_crm');
            $table->string('nombre');
            $table->integer('id_depto_padre_crm');
            $table->integer('id_depto_responsable_crm');
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
        Schema::dropIfExists('catalogo_departamentos');
    }
}
