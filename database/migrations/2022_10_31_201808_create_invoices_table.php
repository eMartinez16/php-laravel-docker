<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('voucher_number');
            $table->string('voucher_type');
            $table->string('sale_point');
            $table->bigInteger('cae');
            $table->date("cae_expiration");
            $table->date('voucher_from')->comment('Inicio de servicio');
            $table->date('voucher_to')->comment('Fin servicio');
            $table->date('voucher_expiration')->comment('Vencimiento comprobante');
            $table->string('concept');
            $table->float('total_net');
            $table->float('total_exempt');
            $table->float('total_iva');
            $table->float('total');
            $table->date('date');
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
        Schema::dropIfExists('invoices');
    }
}
