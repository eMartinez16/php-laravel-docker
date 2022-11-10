<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrainPercentagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grain_percentages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grain_id')
                  ->nullable()
                  ->constrained('grains')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();
            $table->float('max_value');
            $table->float('percentage');
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
        Schema::dropIfExists('grain_percentages');
    }
}
