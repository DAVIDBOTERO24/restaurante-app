<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Proveedores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->increments('id_proveedores');
            $table->string('nombre', 50);
            $table->string('nit', 20)->unique();
            $table->string('telefono', 15)->unique();
            $table->string('correo', 60)->unique()->nullable();
            $table->string('direccion', 50)->nullable();
            $table->unsignedInteger('id_usuario');
            $table->foreign('id_usuario')->references('id_usuarios')->on('usuarios')->onUpdate('restrict')->onDelete('restrict');
            $table->boolean('estado_activacion')->default(true);
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
        Schema::dropIfExists('proveedores');
    }
}
