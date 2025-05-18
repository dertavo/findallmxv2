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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_type',20);
            $table->string('username',50)->unique();
           
            $table->string('email')->unique();
           
            $table->timestamp('email_verified_at')->nullable();

            $table->string('email_confirmation_token')->nullable();

            

            $table->string('password');
            $table->rememberToken();

            /* $table->string('nombre')->nullable();

            $table->unsignedSmallInteger('public_info')->nullable();
            $table->string('telefono',12)->unique()->nullable();
            $table->string('imagen')->nullable();
            $table->string('ap',50)->nullable();
            $table->string('am',100)->nullable();
            $table->string('direccion',100)->nullable();
            $table->string('ciudad',30)->nullable();
            $table->string('estado',30)->nullable();
            $table->string('cp',10)->nullable();

            */

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
        Schema::dropIfExists('users');
    }
};
