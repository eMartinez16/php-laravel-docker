<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDownloadTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('download_tickets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nro_carta_porte');
            $table->bigInteger('nro_ticket');
            $table->bigInteger('nro_contract');
            //desde aca se puede rellenar el campo valor y dto de la vista
            $table->foreignId('grain_category_id')
            ->nullable()
            ->constrained('grain_categories')
            ->cascadeOnUpdate()
            ->nullOnDelete();            
            $table->float('discount_kg');
            //peso bruto
            $table->float('gross_weight');
            $table->dateTime('date_gross_weight');
            //peso tara
            $table->float('tare_weight');
            $table->dateTime('date_tare_weight');
            //peso neto
            $table->float('net_weight');
            $table->float('total_discount');
            $table->float('commercial_net');
            $table->string('condition');
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
        Schema::dropIfExists('download_tickets');
    }
}
