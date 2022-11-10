<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('liable');
            $table->string('business_name');
            $table->string('email');
            $table->bigInteger('CUIT');
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->string('residence')->nullable();
            $table->string('fiscal_condition')->nullable();
            $table->string('payment_condition');
            $table->foreignId('category_id')
            ->nullable()
            ->constrained('client_categories')
            ->cascadeOnUpdate()
            ->nullOnDelete();
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
        Schema::dropIfExists('clients');
    }
}
