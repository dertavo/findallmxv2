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
        Schema::create('contacto_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_contacto');
            $table->unsignedBigInteger('usuario_final');
            $table->unsignedBigInteger('evidence_id')->nullable();
            $table->foreign('usuario_final')->references('id')->on('users');
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
        Schema::dropIfExists('contacto_user');
    }
};
