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
        Schema::create('pruebas_entidad', function (Blueprint $table) {
            $table->id();
            $table->string('status',20);
            $table->smallInteger('handshake');
            $table->string('descripcion',255);
            $table->string('archivo',100)->nullable();

        

            $table->unsignedBigInteger('contact_user');
            $table->foreign('contact_user')->references('id')->on('users');

            $table->unsignedBigInteger('destino_user');
            $table->foreign('destino_user')->references('id')->on('users');

            $table->unsignedBigInteger('entidad_id')->nullable();
            
            $table->foreign('entidad_id')->references('id')->on('entidad')
            ->onDelete('set null');

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
        Schema::dropIfExists('pruebas_entidad');
    }
};
