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
        Schema::create('notificaciones_user', function (Blueprint $table) {
            $table->id();


            $table->string('descripcion',100)->nullable();
            $table->string("tipo",20)->nullable();
            $table->unsignedBigInteger('entidad')->nullable();
            $table->unsignedBigInteger('origen_user')->nullable();
            $table->unsignedBigInteger('destino_user')->nullable();

       
            


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
        Schema::dropIfExists('notificaciones_user');
    }
};
