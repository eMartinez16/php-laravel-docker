<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
          $table->id();
          $table->foreignId('client_id')
          ->nullable()
          ->constrained('clients')
          ->cascadeOnUpdate()
          ->nullOnDelete();
          $table->integer('comprobante_id');
          $table->integer('nro_comprobante');
          $table->decimal('importe')->default(0);
          $table->date('fecha_pago')->nullable();
          $table->integer('medio_pago_id')->nullable();          
          $table->text('observaciones')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
