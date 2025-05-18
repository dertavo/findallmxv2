<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('imagenes_entidad', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->unsignedBigInteger('entidad_id');
            $table->string('archivo',100)->nullable();
            $table->unsignedBigInteger('evidence_id')->nullable();
            $table->foreign('entidad_id')->references('id')->on('entidad')->onDelete('cascade');
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
        //
    }
};
