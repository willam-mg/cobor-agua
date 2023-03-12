<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLecturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lecturas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('hora');
            $table->integer('periodo_gestion');
            $table->integer('periodo_mes');
            $table->integer('lectura_anterior');
            $table->integer('lectura_actual');
            $table->integer('metros_cubicos');
            $table->foreignId('medidor_id')->nullable()->constrained('medidores');
            $table->foreignId('operario_id')->nullable()->constrained('operarios');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lecturas');
    }
}
