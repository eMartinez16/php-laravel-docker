<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGrainCategoryToGrainPercentagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grain_percentages', function (Blueprint $table) {
            $table->foreignId('grain_category_id')
                  ->nullable()
                  ->constrained('grain_categories')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grain_percentages', function (Blueprint $table) {
            $table->dropForeign('grain_percentages_grain_category_id_foreign');
            $table->dropColumn('grain_category_id');
        });
    }
}
