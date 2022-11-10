<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGrainParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grain_params', function (Blueprint $table) {
            $table->dropForeign('grain_params_grain_id_foreign');
            $table->dropColumn('grain_id');
            $table->integer('code')
                  ->nullable()
                  ->default(null)
                  ->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grain_params', function (Blueprint $table) {
            $table->foreignId('grain_id')
                  ->nullable()
                  ->constrained('grains')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();
            $table->dropColumn('code');
        });
    }
}
