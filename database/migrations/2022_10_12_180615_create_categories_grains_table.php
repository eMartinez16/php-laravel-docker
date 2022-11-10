<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesGrainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories_grains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grain_id')
                  ->constrained('grains')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate()
                  ->comments('Grano id');
            $table->foreignId('grain_category_id')
                  ->constrained('grain_categories')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate()
                  ->comments('Rubro de grano relacionado.');
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
        Schema::dropIfExists('categories_grains');
    }
}
