<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrainCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grain_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('grain_id')
                  ->nullable()
                  ->constrained('grains')
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
        Schema::dropIfExists('grain_categories');
    }
}
