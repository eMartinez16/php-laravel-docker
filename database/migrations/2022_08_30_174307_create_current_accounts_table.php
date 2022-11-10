<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrentAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('current_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')
            ->nullable()
            ->constrained('clients')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->integer('comprobante_id');
            $table->integer('debe')->nullable();
            $table->integer('haber')->nullable();
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
        Schema::dropIfExists('current_accounts');
    }
}