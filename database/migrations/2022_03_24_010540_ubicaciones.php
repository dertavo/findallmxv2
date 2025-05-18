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
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entidad_id');            
            $table->foreign('entidad_id')->references('id')->on('entidad')->onDelete('cascade');
            $table->string('latitud',100);
            $table->string('longitud',100);
            $table->boolean('principal');
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
