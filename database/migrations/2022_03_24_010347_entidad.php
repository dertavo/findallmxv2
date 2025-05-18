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
        Schema::create('entidad', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->string('descripcion',200);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('fecha_extravio',20);   
            $table->string('status',20)->default('perdido'); 
            $table->string('recompensa',100)->nullable();
            $table->integer('enabled');
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
        Schema::drop('entidad');
      
    }
};
