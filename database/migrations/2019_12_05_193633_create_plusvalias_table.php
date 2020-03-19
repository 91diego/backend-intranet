<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlusvaliasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plusvalias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('porcentaje_plusvalia');
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
        Schema::dropIfExists('plusvalias');
    }
}
