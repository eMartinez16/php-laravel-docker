<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grain_category_id')
            ->nullable()
            ->constrained('grain_categories')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('ticket_id')
            ->nullable()
            ->constrained('download_tickets')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->float('discount');
            $table->float('value');
            $table->float('discount_kg');
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
        Schema::dropIfExists('ticket_categories');
    }
}
