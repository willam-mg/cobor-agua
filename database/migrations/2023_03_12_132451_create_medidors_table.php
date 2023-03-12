<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedidorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medidores', function (Blueprint $table) {
            $table->id();
            $table->string("codigo_medidor");
            $table->tinyInteger("estado")->default(2)->comment('2 = activo, 3 = cortado por mora');
            $table->foreignId('propiedad_id')->nullable()->constrained('propiedades');
            $table->foreignId('tarifa_id')->nullable()->constrained('tarifas');
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
        Schema::dropIfExists('medidors');
    }
}
