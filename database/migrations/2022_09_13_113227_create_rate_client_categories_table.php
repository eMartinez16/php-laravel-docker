<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateClientCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_client_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_category_id')
            ->nullable()
            ->constrained('client_categories')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('rate_id')
            ->nullable()
            ->constrained('rates')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->float('percentage');
            $table->float('total');
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
        Schema::dropIfExists('rate_client_categories');
    }
}
