<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo', 45);
            $table->string('ci', 45);
            $table->string('telefono', 45);
            $table->text('direccion')->nullable();
            $table->string('rol', 45);
            $table->string('cargo', 45);
            $table->string('fecha_inicio', 45)->nullable();
            $table->string('fecha_fin', 45)->nullable();
            $table->string('src_foto', 100)->nullable();
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
        Schema::dropIfExists('operarios');
    }
}
