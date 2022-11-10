<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartasPortesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cartas_portes', function (Blueprint $table) {
            $table->id();
            $table->integer('tipo');
            $table->bigInteger('sucursal');
            $table->bigInteger('numero');
            $table->bigInteger('ctg');
            $table->string('emision');
            $table->string('estado');
            $table->string('pdf');
            $table->text('json');

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
        Schema::dropIfExists('cartas_portes');
    }
}
