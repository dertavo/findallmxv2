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
        Schema::create('info_user', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->string('ap')->nullable(); // apellido paterno
            $table->string('am')->nullable(); // apellido materno
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('estado')->nullable();
            $table->string('cp')->nullable(); // código postal
            $table->unsignedSmallInteger('public_info')->nullable();
            $table->string('telefono',12)->unique()->nullable();
            $table->rememberToken();

            $table->unsignedBigInteger('user');
            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');;


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
        Schema::dropIfExists('info_user');
    }
};
